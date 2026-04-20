<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsStoreRequest;
use App\Services\Settings\SettingsService;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsService $service,
    ) {}

    public function index()
    {
        return view('pages.admin.settings.index');
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
