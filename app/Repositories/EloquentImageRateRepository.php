<?php

namespace App\Repositories;

use App\Models\ImageRate;
use App\Models\UploadImage;
use App\Repositories\Contracts\ImageRateRepositoryInterface;

class EloquentImageRateRepository implements ImageRateRepositoryInterface
{
    public function paginateWithSummary(array $filters, int $perPage = 15): array
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $rate = $filters['rate'] ?? null;
        $sort = (string) ($filters['sort'] ?? '');

        $query = ImageRate::query();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        if ($rate !== null && $rate !== '' && in_array((int) $rate, [1, 2, 3, 4, 5], true)) {
            $query->where('rate', (int) $rate);
        }

        $summary = (clone $query)
            ->selectRaw('COUNT(*) as total, COALESCE(AVG(rate), 0) as avg_rate')
            ->first();

        $fiveStarCount = (clone $query)->where('rate', 5)->count();
        $lowRateCount = (clone $query)->where('rate', '<=', 2)->count();

        match ($sort) {
            'oldest' => $query->oldest(),
            'highest' => $query->orderByDesc('rate')->latest(),
            'lowest' => $query->orderBy('rate')->latest(),
            default => $query->latest(),
        };

        return [
            'rates' => $query->with('uploadImage')->paginate($perPage)->withQueryString(),
            'summary' => [
                'total' => (int) ($summary->total ?? 0),
                'avg_rate' => round((float) ($summary->avg_rate ?? 0), 2),
                'five_star' => $fiveStarCount,
                'low_rate' => $lowRateCount,
            ],
        ];
    }

    public function findUploadByAreaName(string $normalizedImageName): ?UploadImage
    {
        return UploadImage::query()
            ->whereNotNull('note')
            ->whereRaw("LOCATE('area', LOWER(note)) > 0")
            ->whereRaw(
                "TRIM(LOWER(SUBSTRING(note, LOCATE('area', LOWER(note)) + 4))) = ?",
                [$normalizedImageName]
            )
            ->latest()
            ->first();
    }

    public function create(array $attributes): ImageRate
    {
        return ImageRate::query()->create($attributes);
    }

    public function findOrFail(int $id): ImageRate
    {
        return ImageRate::query()->findOrFail($id);
    }

    public function update(ImageRate $rate, array $attributes): ImageRate
    {
        $rate->update($attributes);

        return $rate->fresh();
    }

    public function delete(ImageRate $rate): void
    {
        $rate->delete();
    }
}
