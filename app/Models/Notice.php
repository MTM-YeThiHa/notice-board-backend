<?php

namespace App\Models;

use App\Events\NoticeCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'distribution_start_date',
        'distribution_end_date',
        'push_flag',
        'public_flag',
        'create_user_id',
        'last_edit_user_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class,'create_user_id', 'id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class,'last_edit_user_id', 'id');
    }
}
