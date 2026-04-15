<?php

namespace App\Services;

use App\Models\FixedImage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;

class FixedServices
{
    protected Carbon $now;

    public function __construct(?Carbon $now = null)
    {
        $this->now = $now ?? now();
    }

    public function setImage(array $data): array
    {
        try {
            $payload = Arr::only($data, [
                'user_id',
                'clients_id',
                'upload_image_id',
            ]);

            $startAt = (clone $this->now)->startOfMonth();
            $endAt = (clone $this->now)->endOfMonth();

            $countUpload = FixedImage::query()
                ->where('clients_id', $payload['clients_id'])
                ->where('user_id', $payload['user_id'])
                ->whereBetween('created_at', [$startAt, $endAt])
                ->count();

            if ($countUpload >= 11) {
                return [
                    'success' => false,
                    'limit' => true,
                    'message' => 'Limit Memilih Foto Tercapai!',
                ];
            }

            $existing = FixedImage::query()
                ->where('upload_image_id', $payload['upload_image_id'])
                ->first();

            $model = $existing ?: FixedImage::create($payload);

            return [
                'success' => true,
                'limit' => false,
                'message' => $existing ? 'Data sudah dipilih sebelumnya.' : 'Data Has Been Saved!',
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
            $image = FixedImage::query()->where('upload_image_id', $id)->first();

            if (!$image) {
                return [
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ];
            }

            $image->delete();

            return [
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => "Error Processing Request: " . $e->getMessage(),
            ];
        }
    }
}
