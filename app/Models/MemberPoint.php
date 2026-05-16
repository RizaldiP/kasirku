<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPoint extends Model
{
    protected $fillable = [
        'member_id', 'transaction_id', 'points', 'type', 'description',
    ];

    public function member(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
