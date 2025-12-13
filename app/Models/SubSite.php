<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubSite extends Model
{
    protected $fillable = [
        'place_id',
        'name',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function acousticStudies(): HasMany
    {
        return $this->hasMany(AcousticStudy::class);
    }

    public function installations(): BelongsToMany
    {
        return $this->belongsToMany(
            Installation::class,
            'installation_sub_site'
        )->withTimestamps();
    }
}
