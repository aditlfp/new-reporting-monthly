<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.admin.settings.index');
    }

    public function store(Request $request)
    {
        $data = $request->only(['api_key', 'theme', 'login_by']);
        Settings::updateOrCreate(
            ['id' => 1],
            [
                'api_key' => $data['api_key'],
                'theme' => $data['theme'],
                'login_by' => $data['login_by']
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Setting Has Ben Saved successfully',
        ], 201);
    }
}
