<?php

namespace App\Http\Controllers;

use App\Models\Latter;
use App\Models\Cover;
use App\Http\Requests\LatterRequest;
use App\Http\Requests\LattersRequest;
use App\Models\Latters;
use Exception;
use Illuminate\Http\Request;

class ReportLettersControllers extends Controller
{
    public function index(Request $request)
    {
        $letters = Latters::with(['cover.client'])->latest()->paginate(10);
        $covers = Cover::get();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $letters,
            ]);
        }

        return view('pages.admin.letters.index', compact('letters', 'covers'));
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

    public function edit(Request $request, $id)
    {
        $latter = Latters::with(['cover.client'])->findOrFail($id);
        $covers = Cover::pluck('id', 'id');
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Get Data Latter By Id.',
                'data' => $latter,
            ], 201);
        }
    }

    public function update(LattersRequest $request, $id)
    {
        $latter = Latters::findOrFail($id);
        $latter->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Latter updated successfully.',
                'data' => $latter,
            ]);
        }

        return to_route('latters.index')->with('success', 'Latter updated successfully.');
    }

    public function destroy(Request $request, $id)
    {   
        try {
            Latters::find($id)->delete();
            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Latter deleted successfully.',
                ]);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return to_route('latters.index')->with('success', 'Latter deleted successfully.');
    }
}
