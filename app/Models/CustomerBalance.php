<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBalance extends Model
{

    protected $table = 'customer_balance';

    protected $fillable = [
        'account_id', 'trn_id', 'trn_type', 'debit', 'credit', 'remarks'
    ];

    public function trn()
    {
        return $this->morphTo();
    }

}
