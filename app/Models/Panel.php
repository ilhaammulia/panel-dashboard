<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Panel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'panels';
    protected $fillable = [
        'name',
        'domain',
        'status',
        'icon'
    ];

    public $incrementing = false;
    protected $keyType = 'string';


    public function UserPanels()
    {
        return $this->hasMany(UserPanel::class);
    }
}
