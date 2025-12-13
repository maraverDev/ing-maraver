<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallationIssue extends Model
{
    protected $fillable = [
        'installation_id',
        'user_id',
        'title',
        'description',
        'status',
        'closed_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
