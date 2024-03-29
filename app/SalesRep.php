<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesRep extends CompanyBaseModel
{

    protected $guarded = [];

    public static function store($results, Company $company)
    {
        foreach ($results as $item) {
            $newItem = new self();
            $item->company_id = $company->id;
            $newItem->fill((array)$item);
            $newItem->save();
        }

    }

}
