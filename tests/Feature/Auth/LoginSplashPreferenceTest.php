<?php

use App\Models\User;
use App\Models\UserSettings;

it('shows splash once after login when splash setting is on', function () {
    $user = User::factory()->create();
    UserSettings::query()->create([
        'user_id' => $user->id,
        'data_theme' => [
            'theme_mode' => 'light',
            'splash_on_login' => true,
        ],
    ]);

    $this->get('/profile')->assertRedirect('/login');

    $response = $this->followingRedirects()->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertSee('data-login-splash="active"', false);

    $this->get('/profile')->assertDontSee('data-login-splash="active"', false);
});

it('does not show splash after login when splash setting is off', function () {
    $user = User::factory()->create();
    UserSettings::query()->create([
        'user_id' => $user->id,
        'data_theme' => [
            'theme_mode' => 'light',
            'splash_on_login' => false,
        ],
    ]);

    $this->get('/profile')->assertRedirect('/login');

    $response = $this->followingRedirects()->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertDontSee('data-login-splash="active"', false);
});
