<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'points', 'total_spent',
    ];

    public static function earnPerAmount(): int
    {
        return (int) Setting::get('points_earn_per_amount', 10000);
    }

    public static function redeemPerDiscount(): int
    {
        return (int) Setting::get('points_redeem_per_discount', 100);
    }

    public static function discountPerUnit(): int
    {
        return (int) Setting::get('points_discount_per_unit', 2000);
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function pointsLog(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MemberPoint::class);
    }
}
