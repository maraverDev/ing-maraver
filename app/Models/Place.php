<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    protected $fillable = [
        'name',
        'max_limiters',
        'limiter_models',
    ];

    protected $casts = [
        'limiter_models' => 'array',
    ];

    public function subSites(): HasMany
    {
        return $this->hasMany(SubSite::class);
    }

    public function installations(): HasMany
    {
        return $this->hasMany(Installation::class);
    }
}
