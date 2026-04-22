<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsStoreRequest;
use App\Models\Settings;
use App\Services\Settings\SettingsService;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsService $service,
    ) {}

    public function index()
    {
        $currentTheme = Settings::query()->value('theme');
        if (!in_array($currentTheme, ['light', 'dark'], true)) {
            $currentTheme = 'light';
        }

        return view('pages.admin.settings.index', [
            'currentTheme' => $currentTheme,
        ]);
    }

    public function store(SettingsStoreRequest $request)
    {
        $this->service->store($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Setting Has Ben Saved successfully',
        ], 201);
    }
}
