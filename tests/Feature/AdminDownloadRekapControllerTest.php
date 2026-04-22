<?php

use App\Models\User;
use App\Services\Media\CoverService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;

function makeAdminAuthUser(): User
{
    $user = new User();
    $user->forceFill([
        'id' => 99,
        'name' => 'Admin',
        'email' => 'admin-download-rekap@test.local',
    ]);

    return $user;
}

function createCoverForValidation(): int
{
    return (int) DB::table('covers')->insertGetId([
        'clients_id' => 1,
        'jenis_rekap' => 'Cleaning Service',
        'img_src_1' => 'covers/sample.png',
        'img_src_2' => 'covers/sample-2.png',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

it('renders admin download rekap page', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminAuthUser());

    $covers = new LengthAwarePaginator(
        collect([
            (object) [
                'id' => 10,
                'img_src_1' => 'covers/sample.png',
                'img_src_2' => null,
                'jenis_rekap' => 'Cleaning Service',
                'has_letter_for_period' => true,
                'client' => (object) ['name' => 'Client A'],
            ],
        ]),
        1,
        12,
        1,
        ['path' => route('admin.download-rekap.index')]
    );

    $service = Mockery::mock(CoverService::class, function (MockInterface $mock) use ($covers) {
        $mock->shouldReceive('downloadRekapIndexData')
            ->once()
            ->with(null, now()->month, now()->year)
            ->andReturn([
                'covers' => $covers,
                'clients' => new Collection([(object) ['id' => 1, 'name' => 'Client A']]),
                'selected_client' => null,
                'selected_month' => now()->month,
                'selected_year' => now()->year,
                'period_label' => 'April ' . now()->year,
            ]);
    });

    app()->instance(CoverService::class, $service);

    $this->get(route('admin.download-rekap.index'))
        ->assertOk()
        ->assertSee('Download Rekap')
        ->assertSee('Client A');
});

it('generates download rekap url', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminAuthUser());
    $coverId = createCoverForValidation();

    $service = Mockery::mock(CoverService::class, function (MockInterface $mock) use ($coverId) {
        $mock->shouldReceive('downloadRekap')
            ->once()
            ->with($coverId, 4, 2026, null)
            ->andReturn('http://localhost/storage/pdf/laporan-client-a-2026-04.pdf');
    });

    app()->instance(CoverService::class, $service);

    $this->postJson(route('admin.download-rekap.generate'), [
        'cover_id' => $coverId,
        'month' => 4,
        'year' => 2026,
    ])
        ->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonPath('url', 'http://localhost/storage/pdf/laporan-client-a-2026-04.pdf');
});

it('rejects invalid payload for generate download rekap', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminAuthUser());
    $coverId = createCoverForValidation();

    $this->postJson(route('admin.download-rekap.generate'), [
        'cover_id' => $coverId,
        'month' => 13,
        'year' => 2026,
    ])->assertStatus(422)->assertJsonValidationErrors(['month']);
});

it('returns 422 when service cannot find letter for selected period', function () {
    $this->withoutMiddleware();
    $this->actingAs(makeAdminAuthUser());
    $coverId = createCoverForValidation();

    $service = Mockery::mock(CoverService::class, function (MockInterface $mock) {
        $mock->shouldReceive('downloadRekap')
            ->once()
            ->with(Mockery::type('int'), 4, 2026, null)
            ->andThrow(new RuntimeException('Data surat untuk client dan periode yang dipilih tidak ditemukan.'));
    });

    app()->instance(CoverService::class, $service);

    $this->postJson(route('admin.download-rekap.generate'), [
        'cover_id' => $coverId,
        'month' => 4,
        'year' => 2026,
    ])
        ->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Data surat untuk client dan periode yang dipilih tidak ditemukan.',
        ]);
});
