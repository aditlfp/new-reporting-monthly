<?php

namespace App\Providers;

use App\Repositories\Contracts\AbsensiUserRepositoryInterface;
use App\Repositories\Contracts\CoverRepositoryInterface;
use App\Repositories\Contracts\ImageRateRepositoryInterface;
use App\Repositories\Contracts\LattersRepositoryInterface;
use App\Repositories\Contracts\MonitoringRepositoryInterface;
use App\Repositories\Contracts\QrCodeRepositoryInterface;
use App\Repositories\Contracts\RoleScopeRepositoryInterface;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Repositories\Contracts\UploadImageRepositoryInterface;
use App\Repositories\Contracts\UserSettingsRepositoryInterface;
use App\Repositories\DbAbsensiUserRepository;
use App\Repositories\EloquentCoverRepository;
use App\Repositories\EloquentImageRateRepository;
use App\Repositories\EloquentLattersRepository;
use App\Repositories\EloquentMonitoringRepository;
use App\Repositories\EloquentQrCodeRepository;
use App\Repositories\EloquentRoleScopeRepository;
use App\Repositories\EloquentSettingsRepository;
use App\Repositories\EloquentUploadImageRepository;
use App\Repositories\EloquentUserSettingsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UploadImageRepositoryInterface::class, EloquentUploadImageRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, EloquentSettingsRepository::class);
        $this->app->bind(UserSettingsRepositoryInterface::class, EloquentUserSettingsRepository::class);
        $this->app->bind(CoverRepositoryInterface::class, EloquentCoverRepository::class);
        $this->app->bind(LattersRepositoryInterface::class, EloquentLattersRepository::class);
        $this->app->bind(QrCodeRepositoryInterface::class, EloquentQrCodeRepository::class);
        $this->app->bind(ImageRateRepositoryInterface::class, EloquentImageRateRepository::class);
        $this->app->bind(MonitoringRepositoryInterface::class, EloquentMonitoringRepository::class);
        $this->app->bind(RoleScopeRepositoryInterface::class, EloquentRoleScopeRepository::class);
        $this->app->bind(AbsensiUserRepositoryInterface::class, DbAbsensiUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
