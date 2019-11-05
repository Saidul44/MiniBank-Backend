<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\CustomerBalance;
use App\Models\Account;

class DepositController extends Controller
{

    public $successStatus = 200;

    public function store(Request $request)
    {

        $account = Account::find($request->account_id);

        if(! $account) {

            $responseData['error'] = true;
            $responseData['status'] = 400;
            $responseData['resMsg'] = "Invalid Account Number. Please try again.";

            return response()->json($responseData);

        }

        $deposit = Deposit::create($request->all());

        $this->createCustomerBalance($deposit);

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "Successfully deposited $deposit->amount Euro";
        $responseData['deposit'] = $deposit;

        return response()->json($responseData);
    }

    public function createCustomerBalance($deposit)
    {
        CustomerBalance::create([
            'account_id' => $deposit->account_id,
            'trn_id' => $deposit->id,
            'trn_type' => $deposit->getNameSpace(),
            'debit' => $deposit->amount,
            'credit' => 0,
            'remarks' => $deposit->remarks
        ]);
    }
}
