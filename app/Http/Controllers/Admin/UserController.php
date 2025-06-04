<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10); 
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua peran untuk pilihan di form
        // Anda bisa filter peran tertentu jika tidak semua boleh dibuat oleh admin
        $roles = Role::all(); 
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hashing password
            'role_id' => $validatedData['role_id'],
            'phone_number' => $validatedData['phone_number'] ?? null,
            // 'email_verified_at' => now(), // Jika ingin langsung verified
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Ambil semua peran untuk pilihan
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        // Update data dasar
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'];
        $user->phone_number = $validatedData['phone_number'] ?? null;

        // Update password hanya jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Tambahkan logika lain di sini jika perlu sebelum menghapus user
        // Misalnya, apa yang terjadi dengan artikel yang ditulisnya, atau order servis yang ditanganinya?
        // Sesuai foreign key constraint kita:
        // - Artikel (author_id): onDelete('restrict') -> user tidak bisa dihapus jika masih punya artikel. Anda perlu handle ini.
        // - ServiceOrders (customer_id): onDelete('restrict') -> user tidak bisa dihapus jika masih punya order.
        // - ServiceOrders (assigned_technician_id): onDelete('set null') -> teknisi dihapus, order jadi tak terassign.
        // - Reviews (customer_id): onDelete('restrict')
        // - Complaints (customer_id): onDelete('restrict')
        // - TechnicianProfile (user_id): onDelete('cascade') -> profil teknisi akan ikut terhapus.

        // Karena banyak 'restrict', Anda mungkin perlu menangani relasi ini dulu atau mengubah constraint.
        // Untuk contoh sederhana saat ini, kita coba langsung delete, tapi ini akan error jika ada constraint restrict yang aktif.
        // Sebaiknya, untuk user yang punya relasi 'restrict', Anda disable tombol hapus atau berikan pesan yang lebih jelas.
        // Atau, implementasikan soft delete untuk user.

        // Untuk sekarang, kita akan coba hapus. Jika ada error foreign key, berarti ada data terkait.
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                             ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Kode error untuk integrity constraint violation
                return redirect()->route('admin.users.index')
                                 ->with('error', 'Pengguna tidak bisa dihapus karena masih memiliki data terkait (misalnya artikel, order servis, dll). Harap periksa dan hapus data terkait terlebih dahulu atau ubah penugasan.');
            }
            // Error lain
            return redirect()->route('admin.users.index')
                             ->with('error', 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage());
        }
    }
}
