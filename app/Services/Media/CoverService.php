<?php

namespace App\Services\Media;

use App\Helpers\FileHelper;
use App\Models\Latters;
use App\Repositories\Contracts\CoverRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CoverService
{
    public function __construct(
        private readonly CoverRepositoryInterface $covers,
        private readonly CoverStorageService $storage,
    ) {}

    public function indexData(): array
    {
        return [
            'covers' => $this->covers->paginateWithClient(),
            'client' => $this->covers->allClients(),
        ];
    }

    public function showById(int $id)
    {
        return $this->covers->findWithClientOrFail($id);
    }

    public function store(array $validated, ?UploadedFile $img1, ?UploadedFile $img2)
    {
        $validated['img_src_1'] = $this->storage->storeLogo($img1);
        $validated['img_src_2'] = $this->storage->storeLogo($img2);

        $cover = $this->covers->create($validated);

        return $cover->load('client');
    }

    public function update(int $id, array $validated, ?UploadedFile $img1, bool $img1Changed, ?UploadedFile $img2, bool $img2Changed)
    {
        $cover = $this->covers->findWithClientOrFail($id);

        if ($img1 && $img1Changed) {
            $validated['img_src_1'] = $this->storage->storeLogo($img1, $cover->img_src_1);
        }

        if ($img2 && $img2Changed) {
            $validated['img_src_2'] = $this->storage->storeLogo($img2, $cover->img_src_2);
        }

        return $this->covers->update($cover, $validated);
    }

    public function destroy(int $id): void
    {
        $cover = $this->covers->findWithClientOrFail($id);
        $this->storage->delete($cover->img_src_1);
        $this->storage->delete($cover->img_src_2);
        $this->covers->delete($cover);
    }

    public function mergeAndStorePdf(UploadedFile $pdf, int $srtId): string
    {
        $dataSrt = Latters::with(['cover.client'])->findOrFail($srtId);

        $periodEn = str_replace(
            ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            ['January','February','March','April','May','June','July','August','September','October','November','December'],
            $dataSrt->period
        );

        $periodDate = Carbon::createFromFormat('F Y', $periodEn);
        $period = $periodDate->format('Y-m');

        $namePDF = $dataSrt->cover->client->name . ' ' . $period;
        $path = $pdf->storeAs('pdf', $namePDF, 'public');

        $file1 = storage_path('app/public/' . $path);
        $file2 = storage_path('app/public/' . $dataSrt->signature);
        $file3 = storage_path('app/public/rekap_foto/' . $period . '-' . $dataSrt->cover->clients_id . '.pdf');

        $finalName = 'laporan-' . str()->slug($dataSrt->cover->client->name) . '-' . str()->slug($period) . '.pdf';
        $finalPath = 'pdf/' . $finalName;

        FileHelper::mergePdfs([$file1, $file2, $file3], storage_path('app/public/' . $finalPath));

        return asset('storage/' . $finalPath);
    }
}
