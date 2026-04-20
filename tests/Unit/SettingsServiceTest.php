<?php

use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Services\Settings\SettingsService;
use Mockery\MockInterface;

it('stores settings via repository', function () {
    $repository = Mockery::mock(SettingsRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('upsert')
            ->once()
            ->with([
                'api_key' => 'abc',
                'theme' => 'light',
                'login_by' => 'email',
            ]);
    });

    $service = new SettingsService($repository);

    $service->store([
        'api_key' => 'abc',
        'theme' => 'light',
        'login_by' => 'email',
    ]);

    expect(true)->toBeTrue();
});
