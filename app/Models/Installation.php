<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installation extends Model
{
    protected $fillable = [
        'place_id',
        'installation_date',
        'limiters_installed',
        'notes',
    ];

    protected $casts = [
        'installation_date' => 'date',
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

}
