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
        $this->service->storeTheme((int) $request->user()->id, $request->validated());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data Theme Has Been created successfully',
            ], 200);
        }

        return back()->with('success', 'Data Theme Has Been created successfully');
    }

    public function update(UserSettingsStoreRequest $request, UserSettings $userSettings)
    {
        $this->service->storeTheme((int) $request->user()->id, $request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Data Theme Has Been updated successfully',
        ]);
    }
}
