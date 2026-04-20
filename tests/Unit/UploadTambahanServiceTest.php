<?php

use App\Models\Jabatan;
use App\Models\User;
use App\Repositories\Contracts\UploadTambahanRepositoryInterface;
use App\Services\UploadTambahanService;
use App\Services\UploadTambahanStorageService;

function makeUploadTambahanServiceUser(string $jabatanName, string $typeJabatan = '', string $codeJabatan = ''): User
{
    $user = new User();
    $user->id = 1;
    $jabatan = new Jabatan();
    $jabatan->forceFill([
        'name_jabatan' => $jabatanName,
        'type_jabatan' => $typeJabatan,
        'code_jabatan' => $codeJabatan,
    ]);
    $user->setRelation('jabatan', $jabatan);

    return $user;
}

it('detects supervisor wilayah correctly', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('Supervisor Wilayah');

    expect($service->isSupervisorWilayah($user))->toBeTrue();
});

it('detects supervisor area correctly', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('Supervisor Area');

    expect($service->isSupervisorArea($user))->toBeTrue();
});

it('allows check access for supervisor pusat', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('Supervisor Pusat');

    $service->ensureUserCanCheck($user);
    expect(true)->toBeTrue();
});

it('recognizes special spv pusat viewer', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('SPV Pusat', '', 'SPV');

    $method = new ReflectionMethod($service, 'isSpecialSpvPusatViewer');
    $method->setAccessible(true);

    expect($method->invoke($service, $user))->toBeTrue();
});

it('maps SPV code to leader targets', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('Supervisor Pusat', '', 'SPV');

    $method = new ReflectionMethod($service, 'resolveSpecialSpvPusatTargetJabatanNames');
    $method->setAccessible(true);

    expect($method->invoke($service, $user))->toBe(['LEADER CS', 'LEADER']);
});

it('maps SPV-W code to danru security target', function () {
    $service = new UploadTambahanService(
        $this->createMock(UploadTambahanRepositoryInterface::class),
        $this->createMock(UploadTambahanStorageService::class),
    );
    $user = makeUploadTambahanServiceUser('Supervisor Pusat', '', 'SPV-W');

    $method = new ReflectionMethod($service, 'resolveSpecialSpvPusatTargetJabatanNames');
    $method->setAccessible(true);

    expect($method->invoke($service, $user))->toBe(['DANRU SECURITY']);
});
