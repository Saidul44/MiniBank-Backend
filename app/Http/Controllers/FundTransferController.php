<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FundTransfer;
use App\Models\CustomerBalance;
use App\Models\Account;

class FundTransferController extends Controller
{

    public $successStatus = 200;

    public function store(Request $request)
    {

        $fromAccount = Account::find($request->from_account_id);

        $toAccount = Account::find($request->to_account_id);

        if(! $fromAccount || ! $toAccount) {

            $responseData['error'] = true;
            $responseData['status'] = 400;
            $responseData['resMsg'] = "Invalid Account Number. Please try again.";
            
            return response()->json($responseData);

        }

        $fundTransfer = FundTransfer::create($request->all());

        $this->createCustomerBalance($fundTransfer);

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "Successfully transfer your fund.";
        $responseData['fund_transfer'] = $fundTransfer;

        return response()->json($responseData);
    }

    public function createCustomerBalance($fundTransfer)
    {
        CustomerBalance::create([
            'account_id' => $fundTransfer->from_account_id,
            'trn_id' => $fundTransfer->id,
            'trn_type' => $fundTransfer->getNameSpace(),
            'debit' => 0,
            'credit' => $fundTransfer->amount,
            'remarks' => $fundTransfer->remarks
        ]);

        CustomerBalance::create([
            'account_id' => $fundTransfer->to_account_id,
            'trn_id' => $fundTransfer->id,
            'trn_type' => $fundTransfer->getNameSpace(),
            'debit' => $fundTransfer->amount,
            'credit' => 0,
            'remarks' => $fundTransfer->remarks
        ]);
    }

}
