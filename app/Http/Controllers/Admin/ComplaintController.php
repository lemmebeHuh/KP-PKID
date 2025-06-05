<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaints = Complaint::with(['customer', 'serviceOrder']) // Eager load customer & service order
                              ->latest() // Urutkan dari terbaru
                              ->paginate(15);
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        return redirect()->route('admin.complaints.edit', $complaint->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        $complaint->load(['customer', 'serviceOrder']); // Eager load relasi
        $statuses = ['Open' => 'Open', 'In Progress' => 'In Progress', 'Resolved' => 'Resolved', 'Closed' => 'Closed'];
        return view('admin.complaints.edit', compact('complaint', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:Open,In Progress,Resolved,Closed',
            'resolved_notes' => 'nullable|string',
        ]);

        $complaint->update($validatedData);

        return redirect()->route('admin.complaints.index')
                         ->with('success', 'Status komplain berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
