<?php

namespace App\Http\Controllers;

use App\Http\Requests\LattersRequest;
use App\Services\Media\LattersService;
use Exception;
use Illuminate\Http\Request;

class ReportLettersControllers extends Controller
{
    public function __construct(
        private readonly LattersService $service,
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->indexData();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $data['letters'],
            ]);
        }

        return view('pages.admin.letters.index', [
            'letters' => $data['letters'],
            'covers' => $data['covers'],
        ]);
    }

    public function create()
    {
        $covers = $this->service->indexData()['covers']->pluck('id', 'id');

        return view('latters.create', compact('covers'));
    }

    public function store(LattersRequest $request)
    {
        $latter = $this->service->store($request->validated(), $request->file('signature'));

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Letter created successfully.',
                'data' => $latter,
            ], 201);
        }

        return redirect()->route('latters.index')->with('success', 'Letter created successfully.');
    }

    public function edit(Request $request, $id)
    {
        $latter = $this->service->showById((int) $id);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Get Data Latter By Id.',
                'data' => $latter,
            ], 201);
        }
    }

    public function show(Request $request, $id)
    {
        $latter = $this->service->showById((int) $id);

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
        $latter = $this->service->update((int) $id, $request->validated());

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
            $this->service->destroy((int) $id);

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
