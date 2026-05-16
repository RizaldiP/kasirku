<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = [
        'letter_number', 'date', 'attachment_count', 'subject',
        'recipient_name', 'recipient_place',
        'sender_name', 'sender_position', 'sender_address',
        'body', 'place', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
