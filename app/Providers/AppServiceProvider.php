<?php

namespace App\Providers;

use App\Models\Contact;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        SecurityScheme::http('bearer');
    }
}
