<?php

use Tests\TestCase;

use App\Models\UploadImage;
use App\Models\User;
use App\Repositories\Contracts\UploadImageRepositoryInterface;
use App\Services\UploadImageService;
use App\Services\UploadImageStorageService;
use Illuminate\Http\Request;
use Mockery\MockInterface;

uses(TestCase::class);

function makeServiceUser(): User {
    $user = new User();
    $user->forceFill([
        'id' => 50,
        'name' => 'Service Tester',
        'nama_lengkap' => 'Service Tester',
        'email' => 'service@test.local',
        'clients_id' => 12,
    ]);

    return $user;
}

it('delegates draft counting to repository', function () {
    $user = makeServiceUser();

    $repository = Mockery::mock(UploadImageRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('countUserDraftsForMonth')->once()->andReturn(4);
    });

    $storage = Mockery::mock(UploadImageStorageService::class);

    $service = new UploadImageService($user, $repository, $storage);

    expect($service->countDrafts($user->id))->toBe(4);
});

it('updates upload images via storage and repository', function () {
    $user = makeServiceUser();
    $upload = new UploadImage([
        'id' => 1,
        'user_id' => $user->id,
        'clients_id' => $user->clients_id,
        'img_before' => 'old-before.jpg',
        'img_proccess' => 'none',
        'img_final' => 'old-final.jpg',
        'note' => 'old',
        'status' => 0,
    ]);

    $request = Request::create('/upload-img-lap/1', 'PUT', [
        'note' => 'new note',
        'status' => 1,
        'temp_img_before' => 'temp-token-before',
        'temp_img_final' => 'temp-token-final',
        'type' => 'submit',
    ]);
    $request->setUserResolver(fn () => $user);

    $repository = Mockery::mock(UploadImageRepositoryInterface::class, function (MockInterface $mock) use ($upload) {
        $mock->shouldReceive('findUserOwnedUpload')->once()->andReturn($upload);
        $mock->shouldReceive('updateUpload')->once()->andReturnUsing(function ($model, $payload) use ($upload) {
            $upload->forceFill($payload);
            return $upload;
        });
    });

    $storage = Mockery::mock(UploadImageStorageService::class, function (MockInterface $mock) {
        $mock->shouldReceive('resolveImageInput')->twice()->andReturn('resolved-path.jpg');
    });

    $service = new UploadImageService($user, $repository, $storage);
    $updated = $service->updateUpload($request, 1);

    expect($updated->img_before)->toBe('resolved-path.jpg')
        ->and($updated->img_final)->toBe('resolved-path.jpg')
        ->and($updated->note)->toBe('new note');
});
