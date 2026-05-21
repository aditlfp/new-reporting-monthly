<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Models\Client;
use App\Models\Clients;
use App\Services\FindingService;
use Illuminate\Http\Request;

class FindingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FindingService $service)
    {
        return view('pages.user.finding.index', $service->getIndexData());
    }

    /**
     * Display the admin listing of findings.
     */
    public function adminIndex()
    {
        $findings = Finding::with('user')->latest()->paginate(15);
        $clients = Clients::all();

        return view('pages.admin.finding.index', compact('findings', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FindingService $service)
    {
        $finding = $service->store($request);

        return response()->json(['message' => 'Temuan berhasil disimpan', 'finding' => $finding], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Finding $finding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finding $finding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finding $finding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finding $finding)
    {
        //
    }

    /**
     * Remove the specified resource from storage (admin).
     */
    public function adminDestroy(Finding $finding)
    {
        $finding->delete();

        return redirect()->route('admin.finding.index')->with('success', 'Temuan berhasil dihapus.');
    }
}
