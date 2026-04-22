<?php

use App\Models\User;
use App\Models\UserSettings;

it('stores user settings theme and splash preference', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from(route('user.settings.index'))->post('/save-settings', [
        'theme_mode' => 'light',
        'splash_on_login' => '1',
    ]);

    $response->assertRedirect(route('user.settings.index'));
    $response->assertSessionHas('success');

    $stored = UserSettings::query()->where('user_id', $user->id)->first();

    expect($stored)->not->toBeNull()
        ->and($stored->data_theme['theme_mode'] ?? null)->toBe('light')
        ->and($stored->data_theme['splash_on_login'] ?? null)->toBeTrue();
});

it('rejects invalid user theme mode', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from(route('user.settings.index'))->post('/save-settings', [
        'theme_mode' => 'silk',
        'splash_on_login' => '1',
    ]);

    $response->assertRedirect(route('user.settings.index'));
    $response->assertSessionHasErrors('theme_mode');
});
