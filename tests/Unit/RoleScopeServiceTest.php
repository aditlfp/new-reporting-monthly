<?php

use App\Models\User;
use App\Repositories\Contracts\RoleScopeRepositoryInterface;
use App\Services\Shared\RoleScopeService;
use Mockery\MockInterface;

function makeRoleUser(string $jabatan): User {
    $user = new User();
    $user->forceFill([
        'id' => 7,
        'kerjasama_id' => 1,
    ]);

    $user->setRelation('jabatan', (object) ['name_jabatan' => $jabatan]);
    $user->setRelation('kerjasama', (object) ['client_id' => 13]);

    return $user;
}

it('resolves security-only scope for danru security', function () {
    $repository = Mockery::mock(RoleScopeRepositoryInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('getJabatanIdsByTypes')->once()->with(['SECURITY'])->andReturn([1, 2]);
        $mock->shouldReceive('getUserIdsByJabatanAndClient')->once()->with([1, 2], 13)->andReturn([99]);
    });

    $service = new RoleScopeService($repository);

    $ids = $service->allowedUserIds(makeRoleUser('DANRU SECURITY'));

    expect($ids)->toBe([99]);
});
