<?php

namespace App\Services;

use App\Models\FixedImage;
use Carbon\Carbon;
use Exception;

class FixedServices
{
    protected $now;

    public function __construct(Carbon $now = null)
    {
       $this->now = $now ?? now();
    }

    public function setImage(array $data)
    {
        try {

            $payload = collect($data)->only([
                'user_id',
                'clients_id',
                'upload_image_id'
            ])->toArray();

            $count_upload = FixedImage::where('clients_id', $payload['clients_id'])
                ->where('user_id', $payload['user_id'])
                ->whereMonth('created_at', $this->now->month)
                ->whereYear('created_at', $this->now->year)
                ->count();

            if ($count_upload >= 11) {
                return response()->json([
                    'success' => false,
                    'limit' => true,
                    'message' => 'Limit Memilih Foto Tercapai!',
                ], 422);
            }

            $model = FixedImage::create($payload);

            return response()->json([
                'success' => true,
                'limit' => false,
                'message' => 'Data Has Been Saved!',
                'data' => $model,
            ], 200);

        } catch (Exception $e) {
            throw new Exception(
                "Error Processing Request: " . $e->getMessage()
            );
        }
    }

    public function removeSelection(int $id)
    {
        try {
            $image = FixedImage::where('upload_image_id', $id)->first();

            if (!$image) {
                return [
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            $image->delete();

            return [
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ];

        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => "Error Processing Request: " . $e->getMessage()
            ];
        }
    }

}
