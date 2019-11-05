<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\CustomerBalance;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\User;
use Auth;


class CustomerController extends Controller
{

    public $successStatus = 200;

    public function index()
    {
        
        $user = User::with('accounts')->find(Auth::id());

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "";
        $responseData['user'] = $user;

        return response()->json($responseData);

    }

    public function balance(Request $request, $accountNo)
    {

        $account = Account::with('user')->find($accountNo);

        if(! $account) {

            $responseData['error'] = true;
            $responseData['status'] = 400;
            $responseData['resMsg'] = "Invalid Account Number. Please try again.";

            return response()->json($responseData);

        }

        $customerBalance = CustomerBalance::select(DB::raw('SUM(debit - credit)  as balance'))
                                        ->where('account_id', $account->id)
                                        ->first();


        if($customerBalance && !isset($customerBalance->balance)) {
            $customerBalance->balance = 0;
        }

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "";
        $responseData['customer_balance'] = $customerBalance;
        $responseData['account'] = $account;

        return response()->json($responseData);
    }

    public function customerInfo(Request $request, $accountNo)
    {
        $account = Account::with('user')->find($accountNo);

        if(! $account) {

            $responseData['error'] = true;
            $responseData['status'] = 400;
            $responseData['resMsg'] = "Invalid Account Number. Please try again.";

            return response()->json($responseData);

        }

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "";
        $responseData['customer_info'] = $account;

        return response()->json($responseData);
    }

    public function newAccountRequest(Request $request)
    {
        $account = Account::create([
                            'user_id' => Auth::id(),
                            'account_type' => $request->account_type,
                            'active_status' => 1
                        ]);

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "Your new account no is $account->id";
        $responseData['customer_info'] = $account;

        return response()->json($responseData);
    }


}
