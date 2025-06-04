<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreServiceRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\UpdateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('category')->latest()->paginate(10); // Eager load kategori
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil kategori dengan tipe 'service'
        $categories = Category::where('type', 'service')->get(); 
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $validatedData = $request->validated();

        // Handle Slug jika ada (jika tidak dihandle di FormRequest)
        if (empty($validatedData['slug']) && !empty($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        }

        Service::create($validatedData);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Jasa servis baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $categories = Category::where('type', 'service')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validatedData = $request->validated();

        if (empty($validatedData['slug']) && !empty($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        } elseif (!empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['slug'], '-');
        }

        $service->update($validatedData);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Jasa servis berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Jasa servis berhasil dihapus!');
    }
}
