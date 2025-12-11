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
                ->whereMonth('created_at', $this->now->month)
                ->whereYear('created_at', $this->now->year)
                ->count();

            if ($count_upload >= 11) {
                return [
                    'success' => false,
                    'message' => 'Limit Memilih Image Tercapai',
                ];
            }

            $model = FixedImage::create($payload);

            return [
                'success' => true,
                'message' => 'Data Has Been Saved!',
                'data' => $model,
            ];

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
