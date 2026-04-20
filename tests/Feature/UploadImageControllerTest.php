<?php

use App\Models\UploadImage;
use App\Models\User;
use App\Services\UploadImageService;
use Mockery\MockInterface;

function makeRouteUser(): User {
    $user = new User();
    $user->forceFill([
        'id' => 99,
        'name' => 'Route Tester',
        'nama_lengkap' => 'Route Tester',
        'email' => 'route@test.local',
        'clients_id' => 10,
    ]);

    return $user;
}

it('returns draft count from upload image service', function () {
    $user = makeRouteUser();

    $this->withoutMiddleware();
    $this->actingAs($user);

    $mock = Mockery::mock(UploadImageService::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('countDrafts')->once()->with($user->id)->andReturn(3);
    });

    app()->instance(UploadImageService::class, $mock);

    $this->getJson(route('v1.count.data'))
        ->assertOk()
        ->assertJson([
            'status' => true,
            'data' => 3,
        ]);
});

it('stores draft through upload image service', function () {
    $user = makeRouteUser();

    $this->withoutMiddleware();
    $this->actingAs($user);

    $upload = new UploadImage([
        'id' => 123,
        'user_id' => $user->id,
        'clients_id' => 10,
        'note' => 'Draft test',
        'status' => 0,
    ]);

    $mock = Mockery::mock(UploadImageService::class, function (MockInterface $mock) use ($upload) {
        $mock->shouldReceive('runOpportunisticTempCleanup')->once();
        $mock->shouldReceive('storeDraft')->once()->andReturn($upload);
    });

    app()->instance(UploadImageService::class, $mock);

    $this->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->postJson(route('upload-images.draft'), [
        'user_id' => $user->id,
        'clients_id' => 10,
        'area' => 'Area test',
        'note' => 'Draft test',
        'status' => 0,
    ])->assertCreated()
        ->assertJson([
            'status' => true,
            'message' => 'Draft saved successfully',
            'data' => [
                'note' => 'Draft test',
                'status' => 0,
            ],
        ]);
});
