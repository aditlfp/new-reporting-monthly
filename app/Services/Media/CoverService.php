<?php

namespace App\Services\Media;

use App\Helpers\FileHelper;
use App\Models\FixedImage;
use App\Models\Latters;
use App\Models\UploadTambahan;
use App\Models\User;
use App\Services\UploadImageStorageService;
use App\Repositories\Contracts\CoverRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use setasign\Fpdi\Fpdi;

class CoverService
{
    public function __construct(
        private readonly CoverRepositoryInterface $covers,
        private readonly CoverStorageService $storage,
        private readonly UploadImageStorageService $uploadStorage,
    ) {}

    public function indexData(): array
    {
        return [
            'covers' => $this->covers->paginateWithClient(),
            'client' => $this->covers->allClients(),
        ];
    }

    public function downloadRekapIndexData(?int $clientId, int $month, int $year): array
    {
        $covers = $this->covers->paginateWithClientForDownload($clientId);
        $period = $this->formatPeriodLabel($month, $year);

        $coverIdsWithLetter = Latters::query()
            ->whereIn('cover_id', $covers->getCollection()->pluck('id')->all())
            ->where('period', $period)
            ->pluck('cover_id')
            ->map(static fn ($id) => (int) $id)
            ->all();

        $latestLetterIds = Latters::query()
            ->select('id', 'cover_id')
            ->whereIn('cover_id', $covers->getCollection()->pluck('id')->all())
            ->where('period', $period)
            ->latest()
            ->get()
            ->unique('cover_id')
            ->mapWithKeys(static fn ($letter) => [(int) $letter->cover_id => (int) $letter->id])
            ->all();

        $covers->setCollection(
            $covers->getCollection()->map(function ($cover) use ($coverIdsWithLetter, $latestLetterIds) {
                $cover->has_letter_for_period = in_array((int) $cover->id, $coverIdsWithLetter, true);
                $cover->latest_letter_id = $latestLetterIds[(int) $cover->id] ?? null;

                return $cover;
            })
        );

        return [
            'covers' => $covers,
            'clients' => $this->covers->allClients(),
            'selected_client' => $clientId,
            'selected_month' => $month,
            'selected_year' => $year,
            'period_label' => $period,
        ];
    }

    public function downloadRekap(int $coverId, int $month, int $year, ?UploadedFile $mainPdf = null): string
    {
        $cover = $this->covers->findWithClientOrFail($coverId);
        $period = $this->formatPeriodLabel($month, $year);

        $letter = Latters::query()
            ->with(['cover.client'])
            ->where('cover_id', $coverId)
            ->where('period', $period)
            ->latest()
            ->first();

        if (!$letter) {
            throw new \RuntimeException('Data surat untuk client dan periode yang dipilih tidak ditemukan.');
        }

        $tempFiles = [];

        try {
            if ($mainPdf instanceof UploadedFile) {
                $coverAndLetterUpload = $mainPdf;
            } else {
                $coverAndLetterPdfPath = $this->buildCoverAndLetterPdf($letter);
                $tempFiles[] = $coverAndLetterPdfPath;

                $coverAndLetterUpload = new UploadedFile(
                    $coverAndLetterPdfPath,
                    basename($coverAndLetterPdfPath),
                    'application/pdf',
                    null,
                    true
                );
            }

            $uploadTambahanFiles = $this->buildUploadTambahanMergeFiles((int) $cover->clients_id, $month, $year, $tempFiles);

            return $this->mergeAndStorePdf($coverAndLetterUpload, (int) $letter->id, [
                'additional_files' => $uploadTambahanFiles,
                'place_additional_before_signature' => true,
                'include_signature_file' => false,
            ]);
        } finally {
            foreach ($tempFiles as $tempFile) {
                if (is_file($tempFile)) {
                    @unlink($tempFile);
                }
            }
        }
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

    public function mergeAndStorePdf(UploadedFile $pdf, int $srtId, array $options = []): string
    {
        $dataSrt = Latters::with(['cover.client'])->findOrFail($srtId);

        $periodEn = str_replace(
            ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            $dataSrt->period
        );

        $periodDate = Carbon::createFromFormat('F Y', $periodEn);
        $period = $periodDate->format('Y-m');

        $namePDF = $dataSrt->cover->client->name . ' ' . $period;
        $path = $pdf->storeAs('pdf', $namePDF, 'public');

        $file1 = storage_path('app/public/' . $path);
        $file2 = storage_path('app/public/' . $dataSrt->signature);
        $file3 = $this->resolveRekapPdfPath(
            (int) $dataSrt->cover->clients_id,
            $period,
            (string) $dataSrt->cover->client->name,
        );
        $additionalFiles = collect($options['additional_files'] ?? [])
            ->filter(static fn ($file) => is_string($file) && $file !== '')
            ->values()
            ->all();
        $placeAdditionalBeforeSignature = (bool) ($options['place_additional_before_signature'] ?? false);
        $includeSignatureFile = (bool) ($options['include_signature_file'] ?? true);

        $finalName = 'laporan-' . str()->slug($dataSrt->cover->client->name) . '-' . str()->slug($period) . '.pdf';
        $finalPath = 'pdf/' . $finalName;

        $this->assertRequiredFileExists($file1, 'main uploaded PDF');
        if ($includeSignatureFile) {
            $this->assertRequiredFileExists($file2, 'signature file');
        }
        $this->assertRequiredFileExists($file3, 'rekap foto PDF');
        foreach ($additionalFiles as $index => $additionalFile) {
            $this->assertRequiredFileExists($additionalFile, 'additional merge file #' . ($index + 1));
        }

        $filesToMerge = [$file1];
        if ($placeAdditionalBeforeSignature) {
            $filesToMerge = array_merge(
                $filesToMerge,
                $additionalFiles,
                $includeSignatureFile ? [$file2, $file3] : [$file3]
            );
        } else {
            $filesToMerge = array_merge(
                $filesToMerge,
                $includeSignatureFile ? [$file2] : [],
                $additionalFiles,
                [$file3]
            );
        }

        FileHelper::mergePdfs($filesToMerge, storage_path('app/public/' . $finalPath));

        return asset('storage/' . $finalPath);
    }

    private function resolveRekapPdfPath(int $clientId, string $period, string $clientName): string
    {
        $expectedRelativePath = 'rekap_foto/' . $period . '-' . $clientId . '.pdf';
        $expectedAbsolutePath = storage_path('app/public/' . $expectedRelativePath);

        if (is_file($expectedAbsolutePath)) {
            return $expectedAbsolutePath;
        }

        Log::warning('Rekap PDF missing. Running fallback storePdf.', [
            'client_id' => $clientId,
            'period' => $period,
            'expected_path' => $expectedRelativePath,
        ]);

        ['path' => $fallbackSourcePath, 'total_rows' => $totalRows, 'period_data' => $periodData] = $this->buildFallbackRekapPdfFromData(
            $clientId,
            $clientName,
            $period
        );

        try {
            $fallbackUpload = new UploadedFile(
                $fallbackSourcePath,
                basename($fallbackSourcePath),
                'application/pdf',
                null,
                true
            );
            $fallbackRelativePath = $this->uploadStorage->storePdf($fallbackUpload, now()->format('Y-m-01'), $clientId);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Fallback storePdf gagal saat file rekap tidak ditemukan: ' . $e->getMessage(), 0, $e);
        } finally {
            if (is_file($fallbackSourcePath)) {
                @unlink($fallbackSourcePath);
            }
        }

        $fallbackAbsolutePath = storage_path('app/public/' . ltrim($fallbackRelativePath, '/'));

        if (!is_file($fallbackAbsolutePath)) {
            throw new \RuntimeException('Fallback storePdf selesai, tetapi file rekap tetap tidak ditemukan: ' . $fallbackRelativePath);
        }

        $samePath = realpath($fallbackAbsolutePath) !== false
            && realpath($expectedAbsolutePath) !== false
            && realpath($fallbackAbsolutePath) === realpath($expectedAbsolutePath);

        if (!$samePath) {
            if (!is_dir(dirname($expectedAbsolutePath))) {
                File::makeDirectory(dirname($expectedAbsolutePath), 0755, true);
            }

            if (!@copy($fallbackAbsolutePath, $expectedAbsolutePath)) {
                throw new \RuntimeException('Fallback storePdf berhasil, tetapi gagal menyalin ke path period rekap: ' . $expectedRelativePath);
            }
        }

        Log::info('Fallback storePdf success for missing rekap PDF.', [
            'client_id' => $clientId,
            'period_surat' => $period,
            'period_data' => $periodData,
            'fallback_path' => $fallbackRelativePath,
            'resolved_path' => $expectedRelativePath,
            'total_rows' => $totalRows,
            'same_path' => $samePath,
        ]);

        return $expectedAbsolutePath;
    }

    private function assertRequiredFileExists(string $path, string $label): void
    {
        if (!is_file($path)) {
            throw new \RuntimeException("File wajib untuk merge tidak ditemukan ({$label}): {$path}");
        }
    }

    private function buildFallbackRekapPdfFromData(int $clientId, string $clientName, string $period): array
    {
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $periodData = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $periodDataText = strtoupper($this->monthNameId((int) $periodData->month) . ' ' . $periodData->year);
        $tmpPath = $tmpDir . '/rekap-fallback-' . $clientId . '-' . $periodData->format('Ym') . '-' . uniqid() . '.pdf';
        $pdf = new Fpdi();
        $pdf->SetMargins(8, 10, 8);
        $pdf->SetAutoPageBreak(false);

        $rows = $this->getFallbackFixedImageRows($clientId, $periodData)->values();

        $pdf->AddPage('L', 'A4');
        $this->renderRekapPageHeader($pdf, 0, $clientName, $periodDataText);
        $this->renderRekapTableHeader($pdf);

        $pageIndex = 0;
        // $this->renderEmptyRekapRow($pdf);

        foreach ($rows as $index => $row) {
            $rowHeight = $index < 2 ? 64.0 : 52.0;

            $pageIndex = $this->ensureRekapRowFitsPage(
                $pdf,
                $rowHeight,
                $pageIndex,
                $clientName,
                $periodDataText
            );
            $this->renderRekapRow($pdf, $index + 1, $row, $rowHeight);
        }

        $pdf->Output($tmpPath, 'F');

        return [
            'path' => $tmpPath,
            'total_rows' => $rows->count(),
            'period_data' => $periodData->format('Y-m'),
        ];
    }

    private function getFallbackFixedImageRows(int $clientId, Carbon $periodData): Collection
    {
        $cleaningServiceUserIds = $this->getCleaningServiceUserIds();

        if (empty($cleaningServiceUserIds)) {
            return collect();
        }

        return FixedImage::query()
            ->with([
                'uploadImage:id,clients_id,img_before,img_proccess,img_final,note',
                'user:id,nama_lengkap,jabatan_id',
                'user.jabatan:id,type_jabatan,name_jabatan',
            ])
            ->where('clients_id', $clientId)
            ->whereMonth('created_at', $periodData->month)
            ->whereYear('created_at', $periodData->year)
            ->whereIn('user_id', $cleaningServiceUserIds)
            ->orderBy('created_at')
            ->get()
            ->map(function ($fixed) {
                return [
                    'img_before' => (string) ($fixed->uploadImage?->img_before ?? ''),
                    'img_proccess' => (string) ($fixed->uploadImage?->img_proccess ?? ''),
                    'img_final' => (string) ($fixed->uploadImage?->img_final ?? ''),
                    'note' => (string) ($fixed->uploadImage?->note ?? '-'),
                ];
            });
    }

    protected function getCleaningServiceUserIds(): array
    {
        return User::query()
            ->whereHas('jabatan', function ($query) {
                $query->whereRaw('LOWER(TRIM(type_jabatan)) = ?', ['cleaning service']);
            })
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->all();
    }

    private function renderRekapPageHeader(Fpdi $pdf, int $pageIndex, string $clientName, string $periodDataText): void
    {
        if ($pageIndex === 0) {
            $pdf->SetY(22);
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(0, 10, 'FOTO KEGIATAN KEBERSIHAN CLEANING SERVICE', 0, 1, 'C');
            $pdf->Cell(0, 10, 'PT SURYA AMANAH CENDIKIA PONOROGO', 0, 1, 'C');
            $pdf->Cell(0, 10, 'AREA ' . strtoupper($clientName), 0, 1, 'C');
            $pdf->Cell(0, 10, 'PERIODE BULAN ' . $periodDataText, 0, 1, 'C');
            $pdf->Ln(8);
            return;
        }

        $pdf->SetY(10);
    }

    private function renderRekapTableHeader(Fpdi $pdf): void
    {
        [$noWidth, $imageWidth, $noteWidth] = $this->rekapTableWidths();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($noWidth, 14, 'NO', 1, 0, 'C');
        $pdf->Cell($imageWidth * 3, 14, 'FOTO PROGRES PENGERJAAN', 1, 0, 'C');
        $pdf->Cell($noteWidth, 14, 'URAIAN PEKERJAAN', 1, 1, 'C');
    }

    private function renderRekapRow(Fpdi $pdf, int $number, array $row, float $rowHeight): void
    {
        [$noWidth, $imageWidth, $noteWidth] = $this->rekapTableWidths();

        $x = (float) $pdf->GetX();
        $y = (float) $pdf->GetY();

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($noWidth, $rowHeight, $number . '.', 1, 0, 'C');

        $imageColumns = ['img_before', 'img_proccess', 'img_final'];

        foreach ($imageColumns as $index => $key) {
            $cellX = $x + $noWidth + ($index * $imageWidth);
            $pdf->Rect($cellX, $y, $imageWidth, $rowHeight);
            $this->drawImageInCell(
                $pdf,
                (string) ($row[$key] ?? ''),
                $cellX,
                $y,
                $imageWidth,
                $rowHeight
            );
        }

        $noteX = $x + $noWidth + (3 * $imageWidth);
        $pdf->Rect($noteX, $y, $noteWidth, $rowHeight);

        $pdf->SetXY($noteX + 4, $y + 6);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell($noteWidth - 8, 6, (string) ($row['note'] ?? '-'), 0, 'L');

        $pdf->SetXY($x, $y + $rowHeight);
    }

    private function renderEmptyRekapRow(Fpdi $pdf): void
    {
        [$noWidth, $imageWidth, $noteWidth] = $this->rekapTableWidths();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($noWidth, 22, '-', 1, 0, 'C');
        $pdf->Cell($imageWidth, 22, '-', 1, 0, 'C');
        $pdf->Cell($imageWidth, 22, '-', 1, 0, 'C');
        $pdf->Cell($imageWidth, 22, '-', 1, 0, 'C');
        $pdf->Cell($noteWidth, 22, 'Data tidak tersedia', 1, 1, 'C');
    }

    private function drawImageInCell(Fpdi $pdf, string $relativePath, float $x, float $y, float $width, float $height): void
    {
        $path = trim($relativePath);

        if ($path === '' || strtolower($path) === 'none') {
            $pdf->SetXY($x, $y + ($height / 2) - 2);
            $pdf->Cell($width, 4, '-', 0, 0, 'C');
            return;
        }

        $absolute = is_file($path)
            ? $path
            : storage_path('app/public/' . ltrim($path, '/'));
        if (!is_file($absolute)) {
            $pdf->SetXY($x, $y + ($height / 2) - 2);
            $pdf->Cell($width, 4, '-', 0, 0, 'C');
            return;
        }

        $imageInfo = @getimagesize($absolute);
        if (!$imageInfo) {
            $pdf->SetXY($x, $y + ($height / 2) - 2);
            $pdf->Cell($width, 4, '-', 0, 0, 'C');
            return;
        }

        [$imgW, $imgH] = $imageInfo;
        if ($imgW <= 0 || $imgH <= 0) {
            $pdf->SetXY($x, $y + ($height / 2) - 2);
            $pdf->Cell($width, 4, '-', 0, 0, 'C');
            return;
        }

        $padding = 2;
        $boxW = $width - ($padding * 2);
        $boxH = $height - ($padding * 2);

        $scale = min($boxW / $imgW, $boxH / $imgH);
        $renderW = $imgW * $scale;
        $renderH = $imgH * $scale;

        $renderX = $x + (($width - $renderW) / 2);
        $renderY = $y + (($height - $renderH) / 2);

        try {
            $pdf->Image($absolute, $renderX, $renderY, $renderW, $renderH);
        } catch (\Throwable) {
            $pdf->SetXY($x, $y + ($height / 2) - 2);
            $pdf->Cell($width, 4, '-', 0, 0, 'C');
        }
    }

    private function monthNameId(int $month): string
    {
        $map = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $map[$month] ?? 'Tidak Diketahui';
    }

    private function rekapTableWidths(): array
    {
        $contentWidth = 281.0; // A4 landscape width(297) - margins(8+8)
        $noWidth = $contentWidth * 0.04;
        $columnWidth = $contentWidth * 0.24;

        return [$noWidth, $columnWidth, $columnWidth];
    }

    private function chunkRekapRows(Collection $rows): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        $chunks = [];
        $all = $rows->values()->all();
        $chunks[] = array_slice($all, 0, 2);

        for ($i = 2; $i < count($all); $i += 3) {
            $chunks[] = array_slice($all, $i, 3);
        }

        return $chunks;
    }

    private function ensureRekapRowFitsPage(
        Fpdi $pdf,
        float $rowHeight,
        int $pageIndex,
        string $clientName,
        string $periodDataText
    ): int {
        $bottomLimit = 200.0; // aman untuk A4 landscape

        if (($pdf->GetY() + $rowHeight) <= $bottomLimit) {
            return $pageIndex;
        }

        $pageIndex++;
        $pdf->AddPage('L', 'A4');
        $this->renderRekapPageHeader($pdf, $pageIndex, $clientName, $periodDataText);
        $this->renderRekapTableHeader($pdf);

        return $pageIndex;
    }

    private function formatPeriodLabel(int $month, int $year): string
    {
        return $this->monthNameId($month) . ' ' . $year;
    }

    private function buildCoverAndLetterPdf(Latters $letter): string
    {
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $tmpPath = $tmpDir . '/download-rekap-cover-letter-' . $letter->id . '-' . uniqid('', true) . '.pdf';

        $existingSource = $this->resolveExistingCoverLetterSourcePdf($letter);
        if ($existingSource !== null) {
            $this->extractFirstTwoPages($existingSource, $tmpPath);
            return $tmpPath;
        }

        $pdf = new Fpdi();
        $pdf->SetMargins(16, 16, 16);

        // Page 1: Cover (fallback renderer when no existing generated PDF is found)
        $pdf->AddPage('P', 'A4');
        $bgImagePath = public_path('img/COVER.png');
        if (is_file($bgImagePath)) {
            try {
                $pdf->Image($bgImagePath, 0, 0, 210, 297);
            } catch (\Throwable) {
                // Keep plain white background if background image cannot be rendered.
            }
        }

        $pdf->SetY(56);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->SetTextColor(50, 60, 139);
        $pdf->Cell(0, 10, strtoupper((string) ($letter->cover->jenis_rekap ?? 'REKAP')), 0, 1, 'C');
        $pdf->SetY(72);
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 10, '(' . strtoupper((string) ($letter->cover->client->name ?? '-')) . ')', 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $this->drawCoverImages($pdf, (string) ($letter->cover->img_src_1 ?? ''), (string) ($letter->cover->img_src_2 ?? ''));
        $pdf->SetY(246);
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 10, 'PERIODE ' . strtoupper((string) $letter->period), 0, 1, 'C');

        // Page 2: Letter fallback page
        $pdf->AddPage('P', 'A4');
        $headerPath = public_path('img/header.png');
        if (is_file($headerPath)) {
            try {
                $pdf->Image($headerPath, 18, 8, 174);
            } catch (\Throwable) {
                // Keep rendering text-only page if image fails.
            }
        }

        $pdf->SetY(38);
        $pdf->SetFont('Times', '', 12);
        $pdf->SetX(34);
        $pdf->Cell(24, 7, 'Nomor', 0, 0, 'L');
        $pdf->Cell(0, 7, ': ' . (string) $letter->latter_numbers, 0, 1, 'L');
        $pdf->SetX(34);
        $pdf->Cell(24, 7, 'Lamp', 0, 0, 'L');
        $pdf->Cell(0, 7, ': ' . (string) $letter->lamp, 0, 1, 'L');
        $pdf->SetX(34);
        $pdf->Cell(24, 7, 'Hal', 0, 0, 'L');
        $pdf->Cell(0, 7, ': ' . (string) $letter->latter_matters, 0, 1, 'L');

        $pdf->SetX(48);
        $pdf->Ln(2);
        $pdf->MultiCell(0, 7, "Kepada Yth.\n" . (string) $letter->letter_to, 0, 'L');
        $pdf->Ln(1);
        $pdf->SetX(48);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(0, 7, "Assalamu'alaikumWarahmatullahiWabarakatuh", 0, 1, 'L');

        $pdf->SetFont('Times', '', 12);
        $pdf->SetX(48);
        $pdf->MultiCell(142, 7, 'Puji syukur kita panjatkan kehadirat Allah SWT yang telah melimpahkan taufiq, hidayah serta kesehatan kepada kita semua. Amin');
        $pdf->SetX(48);
        $pdf->MultiCell(142, 7, 'Bersama dengan ini kami sampaikan Laporan Pekerjaan Cleaning Service di ' . (string) ($letter->cover->client->name ?? '-') . ' untuk periode ' . (string) $letter->period . '.');
        $pdf->SetX(48);
        $pdf->Cell(0, 7, 'Adapun isi laporan pekerjaan kami adalah sebagai berikut:', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetX(48);
        $pdf->SetFont('Times', '', 12);
        $lines = preg_split("/\r\n|\n|\r/", (string) $letter->report_content);
        if (empty($lines)) {
            $pdf->SetX(48);
            $pdf->MultiCell(142, 7, '1. -');
        } else {
            $number = 1;
            foreach ($lines as $line) {
                $line = trim((string) $line);
                if ($line !== '') {
                    $line = preg_replace('/^\d+\.\s*/', '', $line) ?? $line;
                    $pdf->SetX(48);
                    $pdf->MultiCell(142, 7, $number . '. ' . $line);
                    $number++;
                }
            }
        }

        $pdf->SetX(48);
        $pdf->MultiCell(142, 7, 'Besar harapan kami untuk selalu dapat bersama mendukung kemajuan ' . (string) ($letter->cover->client->name ?? '-') . ' serta memberikan "Pelayanan Dengan Lebih Baik" dalam pekerjaan Kritik dan saran sangatlah di harapkan demi terciptanya peningkatan kinerja kami.');
        $pdf->SetX(48);
        $pdf->Cell(142, 7, 'Atas perhatian dan kerjasama Bapak/Ibu kami sampaikan terima kasih.', 0, 1, 'L');
        $pdf->SetX(48);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(142, 7, "Wassalamu'alaikumWarahmatullahiWabarakatuh", 0, 1, 'L');

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(122, 236);
        $pdf->Cell(0, 7, 'Ponorogo, ' . now()->translatedFormat('j F Y'), 0, 1, 'L');
        $pdf->SetX(122);
        $pdf->Cell(0, 7, 'Manager Cleaning Service', 0, 1, 'L');

        $stampPath = public_path('img/stampel.png');
        if (is_file($stampPath)) {
            try {
                $pdf->Image($stampPath, 126, 246, 24, 24);
            } catch (\Throwable) {
                // no-op
            }
        }
        $signPath = public_path('img/ttdParno.png');
        if (is_file($signPath)) {
            try {
                $pdf->Image($signPath, 148, 253, 22, 12);
            } catch (\Throwable) {
                // no-op
            }
        }
        $pdf->SetFont('Times', 'BU', 12);
        $pdf->SetXY(130, 270);
        $pdf->Cell(0, 7, 'Suparno', 0, 1, 'L');

        $pdf->Output($tmpPath, 'F');

        return $tmpPath;
    }

    private function drawCoverImages(Fpdi $pdf, string $imgPath1, string $imgPath2): void
    {
        $leftX = 30.0;
        $rightX = 112.0;
        $y = 136.0;
        $width = 68.0;
        $height = 70.0;

        $this->drawImageInCell($pdf, $imgPath1, $leftX, $y, $width, $height);
        $this->drawImageInCell($pdf, $imgPath2, $rightX, $y, $width, $height);
    }

    private function resolveExistingCoverLetterSourcePdf(Latters $letter): ?string
    {
        $periodEn = str_replace(
            ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            (string) $letter->period
        );

        try {
            $periodDate = Carbon::createFromFormat('F Y', $periodEn);
            $period = $periodDate->format('Y-m');
        } catch (\Throwable) {
            return null;
        }

        $slugClient = str()->slug((string) ($letter->cover->client->name ?? ''));
        $slugPeriod = str()->slug($period);
        $candidate = storage_path('app/public/pdf/laporan-' . $slugClient . '-' . $slugPeriod . '.pdf');

        if (is_file($candidate)) {
            return $candidate;
        }

        return null;
    }

    private function extractFirstTwoPages(string $sourcePdf, string $targetPdf): void
    {
        $pdf = new Fpdi();
        $sourcePageCount = $pdf->setSourceFile($sourcePdf);
        $pagesToExtract = min(2, $sourcePageCount);

        if ($pagesToExtract <= 0) {
            throw new \RuntimeException('PDF sumber cover/surat kosong dan tidak dapat diekstrak.');
        }

        for ($i = 1; $i <= $pagesToExtract; $i++) {
            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
        }

        $pdf->Output($targetPdf, 'F');
    }

    private function buildUploadTambahanMergeFiles(int $clientId, int $month, int $year, array &$tempFiles): array
    {
        $uploads = UploadTambahan::query()
            ->with('items')
            ->where('clients_id', $clientId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $mergeFiles = [];

        foreach ($uploads as $upload) {
            foreach ($upload->items as $item) {
                $absolutePath = storage_path('app/public/' . ltrim((string) $item->file_path, '/'));
                if (!is_file($absolutePath)) {
                    continue;
                }

                $mime = strtolower((string) $item->mime_type);
                if ($mime === 'application/pdf' || str_ends_with(strtolower($absolutePath), '.pdf')) {
                    $mergeFiles[] = $absolutePath;
                    continue;
                }

                if (str_starts_with($mime, 'image/')) {
                    $convertedPath = $this->buildImageAsPdfPage($absolutePath, (string) $item->file_name);
                    $tempFiles[] = $convertedPath;
                    $mergeFiles[] = $convertedPath;
                }
            }
        }

        if (empty($mergeFiles)) {
            $placeholderPath = $this->buildUploadTambahanPlaceholderPdf($month, $year);
            $tempFiles[] = $placeholderPath;
            $mergeFiles[] = $placeholderPath;
        }

        return $mergeFiles;
    }

    private function buildImageAsPdfPage(string $absoluteImagePath, string $fileName): string
    {
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $tmpPath = $tmpDir . '/upload-tambahan-image-' . uniqid('', true) . '.pdf';
        $pdf = new Fpdi();
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage('P', 'A4');

        $ext = strtolower((string) pathinfo($absoluteImagePath, PATHINFO_EXTENSION));
        $renderPath = $absoluteImagePath;
        $generatedPng = null;

        if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $generatedPng = $tmpDir . '/upload-tambahan-image-' . uniqid('', true) . '.png';
            $manager = new ImageManager(new Driver());
            $manager->read($absoluteImagePath)->toPng()->save($generatedPng);
            $renderPath = $generatedPng;
        }

        $this->drawImageInCell($pdf, $renderPath, 10, 10, 190, 277);
        $pdf->Output($tmpPath, 'F');

        if ($generatedPng && is_file($generatedPng)) {
            @unlink($generatedPng);
        }

        return $tmpPath;
    }

    private function buildUploadTambahanPlaceholderPdf(int $month, int $year): string
    {
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $tmpPath = $tmpDir . '/upload-tambahan-placeholder-' . uniqid('', true) . '.pdf';
        $pdf = new Fpdi();
        $pdf->SetMargins(16, 16, 16);
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 9, 'UPLOAD TAMBAHAN', 0, 1, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, 'Tidak ada upload tambahan pada periode ' . $this->monthNameId($month) . ' ' . $year . '.', 0, 'C');
        $pdf->Output($tmpPath, 'F');

        return $tmpPath;
    }
}
