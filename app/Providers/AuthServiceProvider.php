<?php

namespace App\Providers;

use App\Models\Installation;
use App\Policies\InstallationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Installation::class => InstallationPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
