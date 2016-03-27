<?php

namespace App;

use App\Sage\SageoneApi;
use App\Sageone\Api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends CompanyBaseModel
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\CustomerCategory', 'CategoryId');
    }

    public function getNomAttribute() {
        return $this->Name;
    }

    public static function import($company)
    {

        Customer::current($company->id)->delete();

        $response = Api::apiCall("Customer/Get",$company);

        if ($response['status'] == 'error') {

            return $response;

        } else {

            foreach ($response['results']->Results as $item) {
                $newItem = new Customer();
                if (isset($item->Category)) {
                    $item->CategoryId = $item->Category->ID;
                } else {
                    $item->CategoryId = null;
                }
                unset($item->Category);
                $item->company_id = $company->id;
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
