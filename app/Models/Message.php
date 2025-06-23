<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'consultation_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
