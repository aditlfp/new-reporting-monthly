<?php

use App\Models\User;
use App\Services\Media\ImageRateService;
use Mockery\MockInterface;

function makePublicUser(): User {
    $user = new User();
    $user->forceFill([
        'id' => 88,
        'name' => 'Public Tester',
        'email' => 'public@test.local',
    ]);

    return $user;
}

it('validates required rate when storing public rating', function () {
    $this->withoutMiddleware();

    $response = $this->from('/rating-pekerjaan/create')->post(route('rating-pekerjaan.store'), [
        'name' => 'Aditya',
        'email' => 'aditya@example.com',
        'comment' => 'ok',
        'n' => 'area 1',
    ]);

    $response->assertRedirect('/rating-pekerjaan/create');
    $response->assertSessionHasErrors(['rate']);
});

it('handles runtime service error safely when storing public rating', function () {
    $this->withoutMiddleware();

    $mock = Mockery::mock(ImageRateService::class, function (MockInterface $mock) {
        $mock->shouldReceive('store')
            ->once()
            ->andThrow(new RuntimeException('Image target tidak ditemukan untuk rating.'));
    });

    app()->instance(ImageRateService::class, $mock);

    $response = $this->from('/rating-pekerjaan/create')->post(route('rating-pekerjaan.store'), [
        'name' => 'Aditya',
        'email' => 'aditya@example.com',
        'rate' => 4,
        'comment' => 'ok',
        'n' => 'area-x',
    ]);

    $response->assertRedirect('/rating-pekerjaan/create');
    $response->assertSessionHasErrors(['error']);
});
