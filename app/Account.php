<?php

namespace App;

use App\Sage\SageoneApi;
use App\Sageone\Api;

class Account extends CompanyBaseModel
{

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\AccountCategory', 'CategoryId');
    }

    public static function import(Company $company)
    {

        Account::current($company->id)->delete();

        $response = Api::apiCall("Account/Get",$company);

        if ($response['status'] == 'error') {

            return $response;

        } else {

            foreach ($response['results']->Results as $item) {
                $newItem = new Account();
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