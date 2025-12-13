<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallationFile extends Model
{
    protected $fillable = [
        'installation_id',
        'sub_site_id',
        'type',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    public function subSite(): BelongsTo
    {
        return $this->belongsTo(SubSite::class);
    }
}
