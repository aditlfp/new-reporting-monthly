<?php

use App\Services\Media\QrCodeService;
use Mockery\MockInterface;

it('stores qrcode through service safely', function () {
    $this->withoutMiddleware();

    $mock = Mockery::mock(QrCodeService::class, function (MockInterface $mock) {
        $mock->shouldReceive('create')->once()->with('Area A', 'Patroli Pagi');
    });

    app()->instance(QrCodeService::class, $mock);

    $response = $this->post(route('admin-qrcode.store'), [
        'data' => 'Area A',
        'kegiatan' => 'Patroli Pagi',
    ]);

    $response->assertRedirect(route('admin-qrcode.index'));
    $response->assertSessionHas('success');
});

it('handles qrcode service failure safely', function () {
    $this->withoutMiddleware();

    $mock = Mockery::mock(QrCodeService::class, function (MockInterface $mock) {
        $mock->shouldReceive('create')->once()->with('Area A', null)->andThrow(new RuntimeException('failed')); 
    });

    app()->instance(QrCodeService::class, $mock);

    $response = $this->from('/admin/admin-qrcode/create')->post(route('admin-qrcode.store'), [
        'data' => 'Area A',
    ]);

    $response->assertRedirect('/admin/admin-qrcode/create');
    $response->assertSessionHasErrors(['error']);
});
