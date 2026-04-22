<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSettingsStoreRequest;
use App\Models\UserSettings;
use App\Services\Settings\UserSettingsService;

class UserSettingsController extends Controller
{
    public function __construct(
        private readonly UserSettingsService $service,
    ) {}

    public function store(UserSettingsStoreRequest $request)
    {
        $payload = [
            'theme_mode' => (string) $request->validated('theme_mode'),
            'splash_on_login' => $request->boolean('splash_on_login'),
        ];

        $this->service->storeTheme((int) $request->user()->id, $payload);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Pengaturan berhasil disimpan',
            ], 200);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }

    public function update(UserSettingsStoreRequest $request, UserSettings $userSettings)
    {
        $payload = [
            'theme_mode' => (string) $request->validated('theme_mode'),
            'splash_on_login' => $request->boolean('splash_on_login'),
        ];

        $this->service->storeTheme((int) $request->user()->id, $payload);

        return response()->json([
            'status' => true,
            'message' => 'Pengaturan berhasil diperbarui',
        ]);
    }
}
