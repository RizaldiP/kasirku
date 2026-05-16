<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'total_price', 'amount_paid', 'change_amount', 'cashier_id',
        'member_id', 'points_earned', 'points_redeemed', 'discount_from_points',
    ];

    public function cashier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function member(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function pointsLog(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MemberPoint::class);
    }
}
