<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushJob extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'noti_title',
        'noti_body',
        'device_token',
        'link',
        'send_flag',
        'error',
        'send_at',
    ];
}
