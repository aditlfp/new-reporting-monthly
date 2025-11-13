<?php

namespace App\Http\Controllers;

use App\Models\Latter;
use App\Models\Cover;
use App\Http\Requests\LatterRequest;
use App\Http\Requests\LattersRequest;
use App\Models\Latters;
use Illuminate\Http\Request;

class ReportLettersControllers extends Controller
{
    public function index(Request $request)
    {
        $letters = Latters::with('cover')->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $letters,
            ]);
        }

        return view('pages.admin.letters.index', compact('letters'));
    }

    public function create()
    {
        $covers = Cover::pluck('id', 'id');
        return view('latters.create', compact('covers'));
    }

    public function store(LattersRequest $request)
    {
        $latter = Latters::create($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Latter created successfully.',
                'data' => $latter,
            ], 201);
        }

        return to_route('latters.index')->with('success', 'Latter created successfully.');
    }

    public function edit(Latters $latters)
    {
        $covers = Cover::pluck('id', 'id');
        return view('latters.edit', compact('latter', 'covers'));
    }

    public function update(LattersRequest $request, Latters $latters)
    {
        $latters->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Latter updated successfully.',
                'data' => $latter,
            ]);
        }

        return to_route('latters.index')->with('success', 'Latter updated successfully.');
    }

    public function destroy(Request $request, Latters $latters)
    {
        $latters->delete();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Latter deleted successfully.',
            ]);
        }

        return to_route('latters.index')->with('success', 'Latter deleted successfully.');
    }
}
