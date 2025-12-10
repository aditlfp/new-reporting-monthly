<?php

namespace App\Services;

use App\Models\FixedImage;
use Exception;
use Illuminate\Http\Request;

class FixedServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function setImage(array $data)
    {
        try {
            $payload = collect($data)->only([
                'user_id',
                'clients_id',
                'upload_image_id'
            ])->toArray();

            return FixedImage::create($payload);
        } catch (Exception $e) {
            throw new Exception(
                "Error Processing Request: " . $e->getMessage()
            );
        }
    }
}
