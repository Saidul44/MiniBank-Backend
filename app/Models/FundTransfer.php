<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{

    protected $table = 'fund_transfer';

    protected $fillable = [
        'from_account_id', 'to_account_id', 'amount', 'remarks'
    ];


    public function getNameSpace()
    {
        return __CLASS__;
    }

    public function trnName()
    {
        return 'Fund Transfer';
    }

}
