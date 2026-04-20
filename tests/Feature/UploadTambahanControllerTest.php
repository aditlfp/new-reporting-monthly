<?php

use App\Http\Middleware\CheckUserSettingsMiddleware;
use App\Models\Jabatan;
use App\Models\Kerjasama;
use App\Models\User;
use App\Services\UploadTambahanService;
function makeUserForUploadTambahan(string $jabatanName, string $typeJabatan = '', ?int $id = 1): User
{
    $user = new User();
    $user->id = $id;
    $user->name = 'tester';
    $user->nama_lengkap = 'Tester User';
    $user->email = 'tester@example.com';
    $jabatan = new Jabatan();
    $jabatan->forceFill([
        'name_jabatan' => $jabatanName,
        'type_jabatan' => $typeJabatan,
        'code_jabatan' => '',
    ]);
    $kerjasama = new Kerjasama();
    $kerjasama->forceFill([
        'client_id' => 1,
    ]);
    $user->setRelation('jabatan', $jabatan);
    $user->setRelation('kerjasama', $kerjasama);

    return $user;
}

it('returns 403 when non isAccess user opens upload tambahan page', function () {
    $this->withoutMiddleware(CheckUserSettingsMiddleware::class);
    $user = makeUserForUploadTambahan('staff biasa');

    $serviceMock = Mockery::mock(UploadTambahanService::class);
    $serviceMock->shouldReceive('getUserIndexData')
        ->andThrow(new RuntimeException('Akses upload tambahan tidak diizinkan.'));
    $this->app->instance(UploadTambahanService::class, $serviceMock);

    $response = $this->actingAs($user)->get(route('upload-tambahan.index'));
    $response->assertStatus(403);
});

it('validates chunk init mime type for upload tambahan', function () {
    $this->withoutMiddleware(CheckUserSettingsMiddleware::class);
    $user = makeUserForUploadTambahan('leader cs');

    $response = $this->actingAs($user)->post(route('upload-tambahan.chunk.init'), [
        'file_name' => 'test.exe',
        'file_size' => 1024,
        'mime_type' => 'application/x-msdownload',
        'total_chunks' => 1,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('mime_type');
});

it('accepts valid chunk init request for upload tambahan', function () {
    $this->withoutMiddleware(CheckUserSettingsMiddleware::class);
    $user = makeUserForUploadTambahan('leader cs');

    $serviceMock = Mockery::mock(UploadTambahanService::class);
    $serviceMock->shouldReceive('initChunkUpload')
        ->once()
        ->andReturn('upload-id-123');
    $this->app->instance(UploadTambahanService::class, $serviceMock);

    $response = $this->actingAs($user)->post(route('upload-tambahan.chunk.init'), [
        'file_name' => 'dokumen.pdf',
        'file_size' => 4096,
        'mime_type' => 'application/pdf',
        'total_chunks' => 2,
    ]);

    $response->assertOk();
    $response->assertJson([
        'status' => true,
        'upload_id' => 'upload-id-123',
    ]);
});
