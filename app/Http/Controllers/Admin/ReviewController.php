<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review; // Import model Review
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request
    {
        $reviewsQuery = Review::with(['customer', 'serviceOrder']);

        // Logika Filter Rating Bintang
        if ($request->has('rating') && $request->input('rating') != '') {
            $reviewsQuery->where('rating', $request->input('rating'));
        }

        $reviews = $reviewsQuery->latest()->paginate(15);
        $reviews->appends($request->only('rating')); // Append filter ke paginasi

        return view('admin.reviews.index', compact('reviews'));
    }
    // Method lain untuk approve/unapprove, edit, delete bisa ditambahkan nanti
}