<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{

    protected $table = 'deposit';

    protected $fillable = [
        'account_id', 'amount', 'remarks'
    ];



    public function getNameSpace()
    {
        return __CLASS__;
    }

    public function trnName()
    {
        return 'Deposit';
    }

}
