<?php

namespace App\Http\Controllers;

use App\Models\UploadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 2) {
            return view('dashboard');
        } else {
            $uploadDraft = UploadImage::where('user_id', Auth::user()->id)
                ->where('status', 0)
                ->latest()
                ->first();
            return view('pages.user.dashboard', compact('uploadDraft'));
        }
    }
}
