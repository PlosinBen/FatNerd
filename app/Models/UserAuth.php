<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    protected $table = 'user_auth';

    protected $fillable = [
        'provider',
        'provider_user_id',
        'user_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
