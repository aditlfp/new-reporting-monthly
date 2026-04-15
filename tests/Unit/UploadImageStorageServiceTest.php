<?php

use Tests\TestCase;

use App\Services\UploadImageStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class);

it('stores pdf to public storage', function () {
    Storage::fake('public');

    $service = new UploadImageStorageService();
    $pdf = UploadedFile::fake()->create('report.pdf', 100, 'application/pdf');

    $path = $service->storePdf($pdf, '2026-04-01', 33);

    Storage::disk('public')->assertExists($path);
    expect($path)->toBe('rekap_foto/2026-04-33.pdf');
});

it('deletes upload image files from public storage', function () {
    Storage::fake('public');

    Storage::disk('public')->put('upload_images/before/test.jpg', 'before');
    Storage::disk('public')->put('upload_images/final/test.jpg', 'final');

    $upload = new class {
        public string $img_before = 'upload_images/before/test.jpg';
        public string $img_proccess = 'none';
        public string $img_final = 'upload_images/final/test.jpg';
    };

    $service = new UploadImageStorageService();
    $service->deleteUploadFiles($upload);

    Storage::disk('public')->assertMissing('upload_images/before/test.jpg');
    Storage::disk('public')->assertMissing('upload_images/final/test.jpg');
});
