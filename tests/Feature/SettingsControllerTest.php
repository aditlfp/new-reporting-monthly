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
            'theme' => 'dark',
        ]);
    });

    app()->instance(SettingsService::class, $mock);

    $this->postJson(route('admin.set.settings'), [
        'theme' => 'dark',
    ])->assertCreated()->assertJson([
        'status' => true,
    ]);
});

it('rejects invalid admin theme value', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminUser());

    $this->postJson(route('admin.set.settings'), [
        'theme' => 'silk',
    ])->assertStatus(422)->assertJsonValidationErrors('theme');
});
