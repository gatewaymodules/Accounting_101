<?php

namespace App;

use App\Sage\SageoneApi;
use App\Sageone\Api;
use Illuminate\Support\Facades\DB;

class Invoice extends CompanyBaseModel
{

    protected $guarded = [];

    public function customer() {
        return $this->belongsTo('App\Customer', 'CustomerId');
    }

    public function lineitems() {
        return $this->hasMany('App\InvoiceItem', 'invoice_id', 'ID');
    }

    public static function import($company)
    {

        DB::table('invoices')->where('company_id', '=', $company->id)->delete();
        DB::table('invoice_items')->where('company_id', '=', $company->id)->delete();

        $response = Api::apiCall("TaxInvoice/Get?includeDetail=true", $company, true);

        if ($response['status'] == 'error') {

            return $response;

        } else {

            foreach ($response['results']->Results as $item) {
                $newItem = new Invoice();

                unset($item->SalesRepresentative);

                InvoiceItem::import($item->ID, $item->Lines, $company->id);
                unset($item->Lines);

                $newItem->fill((array)$item);
                $newItem->save();
            }

            return [
                'status'  => 'success',
                'results' => count($response['results']->Results) . ' records imported.'
            ];

        }

    }

}
