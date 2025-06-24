<?php

namespace App\Providers;

use App\Models\Terrain;
use App\Models\Booking;
use App\Models\Review;
use App\Policies\TerrainPolicy;
use App\Policies\BookingPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Terrain::class => TerrainPolicy::class,
        Booking::class => BookingPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
