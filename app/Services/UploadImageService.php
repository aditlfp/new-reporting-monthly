<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Models\Jabatan;
use App\Models\PendingSync;
use App\Models\UploadImage;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UploadImageService
{
    protected $user;
    protected $now;

    public function __construct(Authenticatable $user, Carbon $now = null)
    {
        $this->user = $user;
        $this->now = $now ?? now();
    }

    public function store(Request $request)
    {
        $tempFiles = [];

        try {

            foreach (['img_before', 'img_proccess', 'img_final'] as $field) {
                if ($request->hasFile($field)) {
                    $tempFiles[$field] = $request->file($field)->store('temp');
                }
            }

            $data = [
                'img_before' => FileHelper::uploadImage($request->file('img_before'), 'upload_images/before', null, false),
                'img_proccess' => $request->hasFile('img_proccess')
                    ? FileHelper::uploadImage($request->file('img_proccess'), 'upload_images/process', null, false)
                    : null,
                'img_final' => FileHelper::uploadImage($request->file('img_final'), 'upload_images/final', null, false),
            ];

            return UploadImage::create([
                'user_id'      => $this->user->id,
                'clients_id'   => $request->clients_id,
                'note'         => $request->note,
                'status'       => $request->status,
                'max_data'     => 14,
                ...$data
            ]);

        } catch (Exception $e) {

            if ($this->user && User::where('id', $this->user->id)->exists()) {
                    PendingSync::create([
                        'user_id' => $this->user->id,
                        'type' => 'create_post',
                        'payload' => [
                            'temp_files' => $tempFiles,
                            'clients_id' => $request->clients_id,
                            'note' => $request->note,
                            'status' => $request->status,
                        ]
                    ]);
            }

            throw $e;

        }
    }

    public function storeDraft(Request $request): UploadImage
    {
        $user = $request->user();

        $images = [
            'img_before' => 'none',
            'img_proccess' => 'none',
            'img_final' => 'none',
        ];

        if ($request->hasFile('img_before')) {
            $images['img_before'] = FileHelper::uploadImage(
                $request->file('img_before'),
                'upload_images/before',
                null,
                false
            );
        }

        if ($request->hasFile('img_proccess')) {
            $images['img_proccess'] = FileHelper::uploadImage(
                $request->file('img_proccess'),
                'upload_images/process',
                null,
                false
            );
        }

        if ($request->hasFile('img_final')) {
            $images['img_final'] = FileHelper::uploadImage(
                $request->file('img_final'),
                'upload_images/final',
                null,
                false
            );
        }

        return UploadImage::create([
            'user_id'      => $user->id,
            'clients_id'   => $request->clients_id,
            'note'         => $request->note,
            'status'       => $request->status ?? 0,
            'max_data'     => 14,
            ...$images,
        ]);
    }

    public function getUploadImageData(): array
    {
        $user = auth()->user();
        $typeJabatanUser = Str::upper($user->jabatan->name_jabatan);

        // normalisasi typo
        $typeJabatanUser = str_replace('pusat', 'PUSAT', $typeJabatanUser);

        $isSecurity = Str::contains($typeJabatanUser, 'SUPERVISOR PUSAT SECURITY');

        if(!$isSecurity && $typeJabatanUser == 'DANRU SECURITY')
        {
            $type = ['SECURITY'];
        }else{
            $type = $isSecurity
                ? ['SECURITY', 'SUPERVISOR PUSAT SECURITY']
                : [
                    'CLEANING SERVICE',
                    'FRONT OFFICE',
                    'LEADER',
                    'FO',
                    'KASIR',
                    'KARYAWAN',
                    'TAMAN',
                    'TEKNISI'
                ];    
        }

        

        $jabId = Jabatan::whereIn(
                DB::raw('UPPER(type_jabatan)'),
                $type
            )
            ->pluck('id')
            ->toArray();
        $userIds = User::select('id')->whereIn('jabatan_id', $jabId)->get();

        $uploadDraft = UploadImage::where('user_id', $this->user->id)
            ->where('status', 0)
            ->whereMonth('created_at', $this->now->month)
            ->whereYear('created_at', $this->now->year)
            ->get();

        $allImages = UploadImage::where('clients_id', $this->user->kerjasama->client_id)
            ->where('status', 1)
            ->whereMonth('created_at', $this->now->month)
            ->whereYear('created_at', $this->now->year)
            ->whereIn('user_id', $userIds)
            ->latest()
            ->get();

        $imageQuery = UploadImage::where('clients_id', $this->user->kerjasama->client_id)
            ->where('status', 1)
            ->whereMonth('created_at', $this->now->month)
            ->whereYear('created_at', $this->now->year);

        $totalBefore = (clone $imageQuery)
            ->whereNotNull('img_before')
            ->whereNotIn('img_before', ['', 'none'])
            ->count();

        $totalProcess = (clone $imageQuery)
            ->whereNotNull('img_proccess')
            ->whereNotIn('img_proccess', ['', 'none'])
            ->count();

        $totalFinal = (clone $imageQuery)
            ->whereNotNull('img_final')
            ->whereNotIn('img_final', ['', 'none'])
            ->count();

        return [
            'uploadDraft' => $uploadDraft,
            'allImages' => $allImages,
            'totalImageCount' => $totalBefore + $totalProcess + $totalFinal,
        ];
    }
}
