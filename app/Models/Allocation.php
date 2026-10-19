<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $table = 'allocations';

    protected $fillable = [
        'code',
        'name',
        'target_percentage',
        'focus_description',
        'color_hex',
        'icon_name',
    ];

    protected $casts = [
        'target_percentage' => 'integer',
    ];
}
