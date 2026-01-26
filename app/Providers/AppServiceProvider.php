<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Style;
use App\Policies\ProductPolicy;
use App\Policies\StylePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Style::class => StylePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
