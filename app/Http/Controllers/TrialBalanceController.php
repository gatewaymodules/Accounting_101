<?php

namespace App\Http\Controllers;

use App\Sageone\Api;
use App\TrialBalance;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TrialBalanceController extends Controller
{

    public function index()
    {
        $trialbalance = TrialBalance::current($this->company->id)->orderBy('id', 'desc')->get();
        $fromDate = config('reports.trialbalancefromdate');
        $toDate = config('reports.trialbalancetodate');
        return view('trialbalance.index', compact('trialbalance','fromDate','toDate'));
    }

    public function select() {
        return view('trialbalance.select');
    }

    /**
     * Execute the Trial Balance using the chosen parameters
     *
     * Example date range: Date Range:    01/03/2016  to  28/02/2017
     */
    public function store()
    {
        $input = Input::all();

        if ($input['Comparative'] == "1") $input['Comparative'] = true;

        $fromDate = $input['FromDate'];
        $toDate   = $input['ToDate'];

        config(['reports.trialbalancefromdate' => $fromDate]);
        config(['reports.trialbalancetodate' => $toDate]);

        $post = [
            'FromDate'     => $input['FromDate'],
            'ToDate'       => $input['ToDate'],
            'ShowMovement' => $input['ShowMovement'],
            'Comparative'  => $input['Comparative'],
            'CurrencyId'   => 1
        ];

        $results = Api::apiCall('TrialBalance/Export', 0, config('sageoneapi.max_results'), $this->company, $post);

        //dd($response);

        TrialBalance::import($this->company, $results);

        $trialbalance = TrialBalance::current($this->company->id)->orderBy('id', 'desc')->get();

        //dd("Hello");

        return view('trialbalance.index', compact('trialbalance','fromDate','toDate'));

    }

}
