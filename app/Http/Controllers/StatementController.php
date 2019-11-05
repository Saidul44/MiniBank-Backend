<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundTransfer;
use App\Models\CustomerBalance;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class StatementController extends Controller
{

    public $successStatus = 200;

    public function statement(Request $request)
    {
        $account = Account::with('user')->find($request->account_no);

        if(! $account) {

            $responseData['error'] = true;
            $responseData['status'] = 400;
            $responseData['resMsg'] = "Invalid Account Number. Please try again.";

            return response()->json($responseData);

        }

        $statementData = CustomerBalance::where('account_id', $account->id)
                        ->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->end_date)
                        ->get();




        $balanceBD = CustomerBalance::with('trn')->select(DB::raw('SUM(debit - credit)  as balance'))
                        ->where('account_id', $account->id)
                        ->whereDate('created_at', '<', $request->start_date)
                        ->first();

        if($balanceBD && ! isset($balanceBD->balance)) {
            $balanceBD->balance = 0;
        }

        $balance = $balanceBD->balance;

        foreach($statementData as $data) {
            $data->transaction = $data->trn->trnName();
            $balance = $balance + ($data->debit - $data->credit);
            $data->balance = $balance;
        }

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "";
        $responseData['statementData'] = $statementData;
        $responseData['balanceBD'] = $balanceBD->balance;
        $responseData['account_info'] = $account;

        return response()->json($responseData);


    }

}
