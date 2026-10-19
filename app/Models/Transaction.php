<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'code',
        'transaction_date',
        'date_formatted',
        'description',
        'type',
        'allocation_category',
        'amount',
        'receipt_number',
        'pic_name',
        'payment_method',
        'audit_notes',
        'is_verified',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    /**
     * Scope for filter by type
     */
    public function scopeOfType($query, $type)
    {
        if ($type && $type !== 'all') {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope for filter by allocation category
     */
    public function scopeOfCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('allocation_category', $category);
        }
        return $query;
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('description', 'like', "%{$keyword}%")
                  ->orWhere('allocation_category', 'like', "%{$keyword}%")
                  ->orWhere('receipt_number', 'like', "%{$keyword}%")
                  ->orWhere('amount', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }
}
