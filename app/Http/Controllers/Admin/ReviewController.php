<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review; // Import model Review
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with([
                        'customer' => function ($query) { // Eager load customer
                            $query->select('id', 'name', 'email'); // Hanya ambil kolom yg perlu
                        }, 
                        'serviceOrder' => function ($query) { // Eager load service order
                            $query->select('id', 'service_order_number'); // Hanya ambil kolom yg perlu
                        }
                    ])
                    ->latest() // Urutkan dari ulasan terbaru
                    ->paginate(15); // Paginasi

        return view('admin.reviews.index', compact('reviews'));
    }
    // Method lain untuk approve/unapprove, edit, delete bisa ditambahkan nanti
}