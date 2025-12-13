<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcousticStudy extends Model
{
    protected $fillable = [
        'sub_site_id',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function subSite(): BelongsTo
    {
        return $this->belongsTo(SubSite::class);
    }
}
