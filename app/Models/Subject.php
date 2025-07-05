<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'date_of_birth',
        'description',
        'checked',
        'interest',
        'account',
        'credit_card_type',
        'credit_card_number',
        'credit_card_name',
        'credit_card_expiration',
        'from_which_file',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'checked' => 'boolean',
    ];
}
