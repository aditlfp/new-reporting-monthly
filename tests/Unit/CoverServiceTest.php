<?php

use App\Repositories\Contracts\CoverRepositoryInterface;
use App\Services\Media\CoverService;
use App\Services\Media\CoverStorageService;
use App\Services\UploadImageStorageService;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use setasign\Fpdi\Fpdi;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

beforeEach(function () {
    $paths = [
        storage_path('app/public/rekap_foto/2026-04-33.pdf'),
        storage_path('app/public/rekap_foto/2026-05-33.pdf'),
        storage_path('app/public/rekap_foto/2026-06-33.pdf'),
        storage_path('app/public/pdf/laporan-acme-corp-2026-04.pdf'),
        storage_path('app/public/pdf/laporan-acme-corp-2026-05.pdf'),
        storage_path('app/public/pdf/laporan-acme-corp-2026-06.pdf'),
        storage_path('app/public/report_files/signature.pdf'),
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            @unlink($path);
        }
    }
});

function createSimplePdf(string $absolutePath): void
{
    if (!is_dir(dirname($absolutePath))) {
        mkdir(dirname($absolutePath), 0777, true);
    }

    $pdf = new Fpdi();
    $pdf->AddPage();
    $pdf->Output($absolutePath, 'F');
}

function makeUploadedPdf(string $filename): UploadedFile
{
    $sourcePath = storage_path('framework/testing/' . $filename);
    createSimplePdf($sourcePath);

    return new UploadedFile($sourcePath, $filename, 'application/pdf', null, true);
}

function makeLattersPayload(
    string $period = 'April 2026',
    int $clientId = 33,
    string $signature = 'report_files/signature.pdf',
    string $clientName = 'Acme Corp',
): object {
    $client = (object) ['name' => $clientName];
    $cover = (object) ['client' => $client, 'clients_id' => $clientId];

    return (object) [
        'period' => $period,
        'signature' => $signature,
        'cover' => $cover,
    ];
}

function mockFixedImageFallbackQuery(Collection $result): void
{
    $fixedAlias = Mockery::mock('alias:App\Models\FixedImage');
    $query = Mockery::mock();

    $fixedAlias->shouldReceive('query')->once()->andReturn($query);
    $query->shouldReceive('with')->once()->andReturnSelf();
    $query->shouldReceive('where')->once()->with('clients_id', 33)->andReturnSelf();
    $query->shouldReceive('whereMonth')->once()->with('created_at', Mockery::type('int'))->andReturnSelf();
    $query->shouldReceive('whereYear')->once()->with('created_at', Mockery::type('int'))->andReturnSelf();
    $query->shouldReceive('whereIn')->once()->with('user_id', [10, 11])->andReturnSelf();
    $query->shouldReceive('orderBy')->once()->with('created_at')->andReturnSelf();
    $query->shouldReceive('get')->once()->andReturn($result);
}

function mockNoCleaningServiceUsers(): void
{
    $fixedAlias = Mockery::mock('alias:App\Models\FixedImage');
    $fixedAlias->shouldReceive('query')->never();
}

function makeCoverService(UploadImageStorageService $uploadStorage, array $cleaningServiceUserIds = [10, 11]): CoverService
{
    $service = Mockery::mock(CoverService::class, [
        Mockery::mock(CoverRepositoryInterface::class),
        Mockery::mock(CoverStorageService::class),
        $uploadStorage,
    ])->makePartial()->shouldAllowMockingProtectedMethods();

    $service->shouldReceive('getCleaningServiceUserIds')->andReturn($cleaningServiceUserIds);

    return $service;
}

it('merges pdf without fallback when existing rekap file is present', function () {
    $payload = makeLattersPayload();
    $uploaded = makeUploadedPdf('cover-main-existing.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    createSimplePdf(storage_path('app/public/rekap_foto/2026-04-33.pdf'));

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(101)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldNotReceive('storePdf');

    $service = makeCoverService($uploadStorage);

    $url = $service->mergeAndStorePdf($uploaded, 101);

    $expectedFinal = storage_path('app/public/pdf/laporan-acme-corp-2026-04.pdf');
    expect(is_file($expectedFinal))->toBeTrue();
    expect($url)->toContain('storage/pdf/laporan-acme-corp-2026-04.pdf');
});

it('runs fallback storePdf when rekap file is missing', function () {
    $payload = makeLattersPayload(period: 'May 2026');
    $uploaded = makeUploadedPdf('cover-main-fallback.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    mockFixedImageFallbackQuery(collect([
        (object) [
            'uploadImage' => (object) [
                'img_before' => 'none',
                'img_proccess' => 'none',
                'img_final' => 'none',
                'note' => 'Pembersihan lobby',
            ],
        ],
    ]));

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(202)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldReceive('storePdf')
        ->once()
        ->withArgs(function (UploadedFile $pdf, string $month, int $clientId) use ($uploaded) {
            expect($month)->toBe(now()->format('Y-m-01'));
            expect($clientId)->toBe(33);
            expect($pdf->getRealPath())->not->toBe($uploaded->getRealPath());

            return true;
        })
        ->andReturnUsing(function (UploadedFile $pdf, string $month, int $clientId) {
            $relativePath = 'rekap_foto/' . \Carbon\Carbon::parse($month)->format('Y-m') . '-' . $clientId . '.pdf';
            $absolutePath = storage_path('app/public/' . $relativePath);
            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0777, true);
            }

            copy($pdf->getRealPath(), $absolutePath);

            return $relativePath;
        });

    $service = makeCoverService($uploadStorage);

    $url = $service->mergeAndStorePdf($uploaded, 202);

    $expectedFinal = storage_path('app/public/pdf/laporan-acme-corp-2026-05.pdf');
    $expectedRekapForPeriod = storage_path('app/public/rekap_foto/2026-05-33.pdf');
    expect(is_file($expectedFinal))->toBeTrue();
    expect(is_file($expectedRekapForPeriod))->toBeTrue();
    expect($url)->toContain('storage/pdf/laporan-acme-corp-2026-05.pdf');
});

it('creates valid fallback rekap pdf when fixed image dataset is empty', function () {
    $payload = makeLattersPayload(period: 'May 2026');
    $uploaded = makeUploadedPdf('cover-main-fallback-empty.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    mockFixedImageFallbackQuery(collect([]));

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(252)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldReceive('storePdf')
        ->once()
        ->withArgs(function (UploadedFile $pdf, string $month, int $clientId) use ($uploaded) {
            expect($month)->toBe(now()->format('Y-m-01'));
            expect($clientId)->toBe(33);
            expect($pdf->getRealPath())->not->toBe($uploaded->getRealPath());

            return true;
        })
        ->andReturnUsing(function (UploadedFile $pdf, string $month, int $clientId) {
            $relativePath = 'rekap_foto/' . \Carbon\Carbon::parse($month)->format('Y-m') . '-' . $clientId . '.pdf';
            $absolutePath = storage_path('app/public/' . $relativePath);
            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0777, true);
            }

            copy($pdf->getRealPath(), $absolutePath);

            return $relativePath;
        });

    $service = makeCoverService($uploadStorage);

    $url = $service->mergeAndStorePdf($uploaded, 252);

    $expectedFinal = storage_path('app/public/pdf/laporan-acme-corp-2026-05.pdf');
    expect(is_file($expectedFinal))->toBeTrue();
    expect($url)->toContain('storage/pdf/laporan-acme-corp-2026-05.pdf');
});

it('creates valid fallback rekap pdf when no cleaning service users exist', function () {
    $payload = makeLattersPayload(period: 'May 2026');
    $uploaded = makeUploadedPdf('cover-main-fallback-no-users.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    mockNoCleaningServiceUsers();

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(272)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldReceive('storePdf')
        ->once()
        ->withArgs(function (UploadedFile $pdf, string $month, int $clientId) use ($uploaded) {
            expect($month)->toBe(now()->format('Y-m-01'));
            expect($clientId)->toBe(33);
            expect($pdf->getRealPath())->not->toBe($uploaded->getRealPath());

            return true;
        })
        ->andReturnUsing(function (UploadedFile $pdf, string $month, int $clientId) {
            $relativePath = 'rekap_foto/' . \Carbon\Carbon::parse($month)->format('Y-m') . '-' . $clientId . '.pdf';
            $absolutePath = storage_path('app/public/' . $relativePath);
            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0777, true);
            }

            copy($pdf->getRealPath(), $absolutePath);

            return $relativePath;
        });

    $service = makeCoverService($uploadStorage, []);

    $url = $service->mergeAndStorePdf($uploaded, 272);

    $expectedFinal = storage_path('app/public/pdf/laporan-acme-corp-2026-05.pdf');
    expect(is_file($expectedFinal))->toBeTrue();
    expect($url)->toContain('storage/pdf/laporan-acme-corp-2026-05.pdf');
});

it('does not fail when fallback path equals expected period path', function () {
    $payload = makeLattersPayload(period: 'April 2026');
    $uploaded = makeUploadedPdf('cover-main-fallback-same-path.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    mockNoCleaningServiceUsers();

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(292)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldReceive('storePdf')
        ->once()
        ->withArgs(function (UploadedFile $pdf, string $month, int $clientId) use ($uploaded) {
            expect($month)->toBe(now()->format('Y-m-01'));
            expect($clientId)->toBe(33);
            expect($pdf->getRealPath())->not->toBe($uploaded->getRealPath());

            return true;
        })
        ->andReturnUsing(function (UploadedFile $pdf, string $month, int $clientId) {
            $relativePath = 'rekap_foto/' . \Carbon\Carbon::parse($month)->format('Y-m') . '-' . $clientId . '.pdf';
            $absolutePath = storage_path('app/public/' . $relativePath);
            if (!is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0777, true);
            }

            copy($pdf->getRealPath(), $absolutePath);

            return $relativePath;
        });

    $service = makeCoverService($uploadStorage, []);

    $url = $service->mergeAndStorePdf($uploaded, 292);

    $expectedFinal = storage_path('app/public/pdf/laporan-acme-corp-2026-04.pdf');
    expect(is_file($expectedFinal))->toBeTrue();
    expect($url)->toContain('storage/pdf/laporan-acme-corp-2026-04.pdf');
});

it('throws explicit exception when fallback storePdf fails', function () {
    $payload = makeLattersPayload(period: 'June 2026');
    $uploaded = makeUploadedPdf('cover-main-fallback-fail.pdf');

    createSimplePdf(storage_path('app/public/' . $payload->signature));
    mockFixedImageFallbackQuery(collect([]));

    $lattersAlias = Mockery::mock('alias:App\Models\Latters');
    $lattersAlias->shouldReceive('with')->once()->with(['cover.client'])->andReturnSelf();
    $lattersAlias->shouldReceive('findOrFail')->once()->with(303)->andReturn($payload);

    $uploadStorage = Mockery::mock(UploadImageStorageService::class);
    $uploadStorage->shouldReceive('storePdf')
        ->once()
        ->andThrow(new RuntimeException('forced fallback failure'));

    $service = makeCoverService($uploadStorage);

    expect(fn () => $service->mergeAndStorePdf($uploaded, 303))
        ->toThrow(RuntimeException::class, 'Fallback storePdf gagal');
});
