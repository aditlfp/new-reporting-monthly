<?php

namespace App\Providers;

use App\Models\UserSettings;
use App\Repositories\Contracts\UploadImageRepositoryInterface;
use App\Repositories\EloquentUploadImageRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UploadImageRepositoryInterface::class, EloquentUploadImageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
