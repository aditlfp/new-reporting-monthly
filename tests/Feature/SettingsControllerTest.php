<?php

use App\Models\User;
use App\Services\Settings\SettingsService;
use Mockery\MockInterface;

function makeAdminUser(): User {
    $user = new User();
    $user->forceFill([
        'id' => 44,
        'name' => 'Admin',
        'email' => 'admin@test.local',
    ]);

    return $user;
}

it('stores admin settings through service', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminUser());

    $mock = Mockery::mock(SettingsService::class, function (MockInterface $mock) {
        $mock->shouldReceive('store')->once()->with([
            'api_key' => 'key-123',
            'theme' => 'dark',
            'login_by' => 'email',
        ]);
    });

    app()->instance(SettingsService::class, $mock);

    $this->postJson(route('admin.set.settings'), [
        'api_key' => 'key-123',
        'theme' => 'dark',
        'login_by' => 'email',
    ])->assertCreated()->assertJson([
        'status' => true,
    ]);
});
