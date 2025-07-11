<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use App\Models\SubRack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RackController extends Controller
{
    /**
     * Display a listing of the racks.
     */
    public function index(): Response
    {
        $racks = Rack::with(['subRacks', 'creator'])
            ->withCount(['files', 'subRacks'])
            ->latest()
            ->paginate(10);
        
        return Inertia::render('Admin/Racks/Index', [
            'racks' => $racks,
        ]);
    }

    /**
     * Show the form for creating a new rack.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Racks/Create');
    }

    /**
     * Store a newly created rack in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:racks',
            'description' => 'nullable|string',
        ]);

        Rack::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.racks.index')
            ->with('success', 'Rack created successfully.');
    }

    /**
     * Show the form for editing the specified rack.
     */
    public function edit(Rack $rack): Response
    {
        $rack->load(['subRacks', 'creator']);
        
        return Inertia::render('Admin/Racks/Edit', [
            'rack' => $rack,
        ]);
    }

    /**
     * Update the specified rack in storage.
     */
    public function update(Request $request, Rack $rack)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:racks,name,' . $rack->id,
            'description' => 'nullable|string',
        ]);

        $rack->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.racks.index')
            ->with('success', 'Rack updated successfully.');
    }

    /**
     * Remove the specified rack from storage.
     */
    public function destroy(Rack $rack)
    {
        $rack->delete();

        return back()->with('success', 'Rack deleted successfully.');
    }

    /**
     * Show the form for creating a new sub-rack.
     */
    public function createSubRack(Rack $rack): Response
    {
        return Inertia::render('Admin/SubRacks/Create', [
            'rack' => $rack,
        ]);
    }

    /**
     * Store a newly created sub-rack in storage.
     */
    public function storeSubRack(Request $request, Rack $rack)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SubRack::create([
            'rack_id' => $rack->id,
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.racks.index')
            ->with('success', 'Sub-rack berhasil ditambahkan ke rak: ' . $rack->name);
    }

    /**
     * Remove the specified sub-rack from storage.
     */
    public function destroySubRack(SubRack $subRack)
    {
        $subRack->delete();

        return back()->with('success', 'Sub-rack deleted successfully.');
    }
}
