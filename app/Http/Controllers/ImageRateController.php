<?php

namespace App\Http\Controllers;

use App\Models\ImageRate;
use App\Models\UploadImage;
use Illuminate\Http\Request;

class ImageRateController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $rate = $request->query('rate');
        $sort = $request->query('sort', '');

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

        $rates = $query->with('uploadImage')->paginate(15)->withQueryString();

        return view('pages.admin.rating_image.index', [
            'rates' => $rates,
            'summary' => [
                'total' => (int) ($summary->total ?? 0),
                'avg_rate' => round((float) ($summary->avg_rate ?? 0), 2),
                'five_star' => $fiveStarCount,
                'low_rate' => $lowRateCount,
            ],
        ]);
    }

    public function create(Request $request)
    {
        // --- AMBIL PARAMETER DARI URL INTENDED ---
        $intendedUrl = session()->get('url.intended');
        $nValue = null; // Inisialisasi
        $uploadPreview = null;

        if ($intendedUrl) {
            $parsedUrl = parse_url($intendedUrl);
            if (isset($parsedUrl['query'])) {
                $queryParams = [];
                parse_str($parsedUrl['query'], $queryParams);
                $nValue = $queryParams['n'] ?? null;
            }
        }

        if (!empty($nValue)) {
            $normalizedImageName = strtolower(trim((string) $nValue));

            $uploadPreview = UploadImage::query()
                ->whereNotNull('note')
                ->whereRaw("LOCATE('area', LOWER(note)) > 0")
                ->whereRaw(
                    "TRIM(LOWER(SUBSTRING(note, LOCATE('area', LOWER(note)) + 4))) = ?",
                    [$normalizedImageName]
                )
                ->latest()
                ->first();
        }

        return view('pages.user.rating_image.create', compact('nValue', 'uploadPreview'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $imageName = trim((string) $request->input('n', ''));
        $normalizedImageName = strtolower($imageName);

        $image = UploadImage::query()
            ->whereNotNull('note')
            // Strict match: only compare text after "Area" from note.
            ->whereRaw("LOCATE('area', LOWER(note)) > 0")
            ->whereRaw(
                "TRIM(LOWER(SUBSTRING(note, LOCATE('area', LOWER(note)) + 4))) = ?",
                [$normalizedImageName]
            )
            ->latest()
            ->first();
            
        $data = [
            'upload_image_id' => $image->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rate' => $request->input('rate'),
            'comment' => $request->input('comment')
        ];

        ImageRate::create($data);

        return redirect('/')->with('success', 'Rating berhasil disimpan');
    }

    public function show(ImageRate $imageRate)
    {
        return view('pages.admin.rating_image.show', compact('imageRate'));
    }

    public function edit(ImageRate $imageRate)
    {
        return view('pages.admin.rating_image.edit', compact('imageRate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rate' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        $imageRate = ImageRate::findOrFail($id);
        $imageRate->update($request->only(['name', 'email', 'rate', 'comment']));

        return redirect()->route('admin-rating-image.index')->with('success', 'Rating berhasil diupdate');
    }

    public function destroy($id)
    {
        $imageRate = ImageRate::findOrFail($id);
        $imageRate->delete();

        return redirect()->route('admin-rating-image.index')->with('success', 'Rating berhasil dihapus');
    }
}
