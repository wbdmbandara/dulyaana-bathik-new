<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $table = 'bank_details';

    protected $fillable = [
        'account_name',
        'account_number',
        'bank_name',
        'branch',
        'branch_code',
        'instructions',
        'user_id',
    ];
}
