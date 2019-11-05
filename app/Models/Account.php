<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $fillable = [
        'user_id', 'account_type', 'active_status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
