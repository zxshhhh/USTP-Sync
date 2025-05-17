<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FitbitToken extends Model
{
    use HasFactory;
    // The table name (optional if it follows Laravel convention 'fitbit_tokens')
    protected $table = 'fitbit_tokens';

    // Which attributes can be mass-assigned
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'scope',
        'expires_in',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // If you want timestamps (created_at, updated_at) - default is true, so no need to specify

    // Optional: If you want to disable timestamps for some reason:
    // public $timestamps = false;
}
