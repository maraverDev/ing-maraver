<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installation extends Model
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    protected $fillable = [
        'place_id',
        'installation_date',
        'limiters_installed',
        'notes',
        'status',
    ];

    protected $casts = [
        'installation_date' => 'datetime',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function subSites(): BelongsToMany
    {
        return $this->belongsToMany(
            SubSite::class,
            'installation_sub_site'
        )->withTimestamps();
    }

    public function files(): HasMany
    {
        return $this->hasMany(InstallationFile::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InstallationLog::class)->orderByDesc('created_at');
    }
    public function issues(): HasMany
    {
        return $this->hasMany(InstallationIssue::class)
            ->orderByDesc('created_at');
    }
    public function creationLog()
    {
        return $this->hasOne(InstallationLog::class)
            ->where('action', 'Creación de instalación');
    }
    public function getExpectedFilesCountAttribute(): int
    {
        return $this->subSites->count() * 3;
    }

    public function getUploadedFilesCountAttribute(): int
    {
        return $this->files ? $this->files->count() : 0;
    }

    public function getInstallationStatusAttribute(): string
    {
        if ($this->uploaded_files_count === 0) {
            return 'empty';
        }

        if ($this->uploaded_files_count < $this->expected_files_count) {
            return 'partial';
        }

        return 'complete';
    }

}
