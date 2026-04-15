<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\FileHelper;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('uploads:cleanup-temp {--ttl=30 : TTL minute untuk menghapus temp upload orphan}', function () {
    $ttl = (int) $this->option('ttl');
    $result = FileHelper::cleanupOrphanTempUploads($ttl);

    $this->info("Cleanup selesai. Deleted: {$result['deleted_dirs']} direktori, Freed: {$result['freed_bytes']} bytes.");
})->purpose('Cleanup orphan temporary chunk upload files');
