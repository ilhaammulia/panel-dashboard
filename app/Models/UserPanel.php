<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserPanel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'user_panels';
    protected $fillable = [
        'user_id',
        'panel_id',
        'name',
        'status',
        'domain',
        'telegram_bot_token',
        'telegram_chat_id',
        'expired_at'
    ];

    public $incrementing = false;
    protected $keyType = 'string';


    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Panel()
    {
        return $this->belongsTo(Panel::class, 'panel_id', 'id');
    }

    public function Victims()
    {
        return $this->hasMany(Victim::class);
    }
}
