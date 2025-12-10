<?php

namespace App\Http\Controllers;

use App\Models\UserSettings;
use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    
    public function store(Request $request)
    {

        $data = $request->only([
            'bg_color',
            'text_color_1',
            'text_color_2',
            'primary_color',
            'secondary_color',
            'error_color',
        ]);

        UserSettings::updateOrCreate(
            ['user_id' => auth()->id()],   // kondisi
            ['data_theme' => $data]        // data yang diupdate
        );

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data Theme Has Been created successfully',
            ], 200);
        }
    }

    public function update(Request $request, UserSettings $userSettings)
    {
        //
    }

}
