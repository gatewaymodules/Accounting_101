<?php

namespace App;

class TrialBalance extends CompanyBaseModel
{
    protected $guarded = [];

    public function accounttype() {
        return $this->belongsTo('\App\AccountType', 'AccountTypeId');
    }

    public function account() {
        return $this->belongsTo('\App\Account', 'AccountId');
    }

    public static function import(Company $company, $results)
    {

        TrialBalance::current($company->id)->delete();

        //dd($response);

        foreach ($results as $item) {
            $newItem          = new TrialBalance();
            $item->company_id = $company->id;
            $newItem->fill((array)$item);
            $newItem->save();
        }
    }
}
