<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSetting extends Model
{
    use HasFactory;

    protected $table = 'bank_settings';

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'bank_code',
        'qris_nmid',
        'whatsapp_number',
        'address',
        'active_period',
        'starting_balance',
    ];

    protected $casts = [
        'starting_balance' => 'decimal:2',
    ];
}
