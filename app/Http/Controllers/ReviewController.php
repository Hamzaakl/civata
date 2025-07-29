<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Review::with(['booking.service', 'reviewer', 'reviewee']);

        // Filter by user role
        if ($user->isServiceProvider()) {
            // Hizmet sağlayıcı kendi aldığı reviewları görür
            $query->where('reviewee_id', $user->id);
        } elseif ($user->isCustomer()) {
            // Müşteri kendi yazdığı reviewları görür
            $query->where('reviewer_id', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $reviews = $query->latest()->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    public function create(Booking $booking)
    {
        // Sadece completed booking için review yazılabilir
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'Sadece tamamlanan hizmetler için değerlendirme yapabilirsiniz.');
        }

        // Sadece müşteri review yazabilir
        if (Auth::id() !== $booking->customer_id) {
            abort(403);
        }

        // Daha önce review yazılmış mı kontrol et
        $existingReview = Review::where('booking_id', $booking->id)
                               ->where('reviewer_id', Auth::id())
                               ->first();

        if ($existingReview) {
            return redirect()->route('reviews.show', $existingReview)
                           ->with('info', 'Bu hizmet için zaten değerlendirme yapmışsınız.');
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Validations
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'Sadece tamamlanan hizmetler için değerlendirme yapabilirsiniz.');
        }

        if (Auth::id() !== $booking->customer_id) {
            abort(403);
        }

        // Daha önce review yazılmış mı kontrol et
        $existingReview = Review::where('booking_id', $booking->id)
                               ->where('reviewer_id', Auth::id())
                               ->first();

        if ($existingReview) {
            return redirect()->route('reviews.show', $existingReview)
                           ->with('error', 'Bu hizmet için zaten değerlendirme yapmışsınız.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Fotoğrafları yükle
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                $images[] = $path;
            }
        }

        // Review oluştur
        $review = Review::create([
            'booking_id' => $booking->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $booking->provider_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => json_encode($images),
            'is_approved' => true, // Otomatik onay
        ]);

        return redirect()->route('reviews.show', $review)
                       ->with('success', 'Değerlendirmeniz başarıyla gönderildi!');
    }

    public function show(Review $review)
    {
        // Public erişim - herkes görebilir
        $review->load(['booking.service', 'reviewer', 'reviewee']);
        
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        // Sadece kendi review'ını düzenleyebilir
        if (Auth::id() !== $review->reviewer_id) {
            abort(403);
        }

        // Sadece 24 saat içinde düzenlenebilir
        if ($review->created_at->diffInHours(now()) > 24) {
            return redirect()->back()->with('error', 'Değerlendirmeler sadece 24 saat içinde düzenlenebilir.');
        }

        $review->load(['booking.service', 'reviewee']);
        
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Authorization
        if (Auth::id() !== $review->reviewer_id) {
            abort(403);
        }

        if ($review->created_at->diffInHours(now()) > 24) {
            return redirect()->back()->with('error', 'Değerlendirmeler sadece 24 saat içinde düzenlenebilir.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Yeni fotoğrafları yükle
        $images = json_decode($review->images, true) ?? [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                $images[] = $path;
            }
        }

        // Review güncelle
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => json_encode($images),
            'is_approved' => true, // Yeniden onaylandı
        ]);

        return redirect()->route('reviews.show', $review)
                       ->with('success', 'Değerlendirmeniz başarıyla güncellendi!');
    }

    public function destroy(Review $review)
    {
        // Sadece kendi review'ını silebilir
        if (Auth::id() !== $review->reviewer_id) {
            abort(403);
        }

        // Fotoğrafları sil
        $images = json_decode($review->images, true) ?? [];
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }

        $review->delete();

        return redirect()->route('reviews.index')
                       ->with('success', 'Değerlendirme başarıyla silindi.');
    }

    public function deleteImage(Review $review, $imageIndex)
    {
        // Sadece kendi review'ından resim silebilir
        if (Auth::id() !== $review->reviewer_id) {
            abort(403);
        }

        $images = json_decode($review->images, true) ?? [];
        
        if (isset($images[$imageIndex])) {
            // Dosyayı sil
            Storage::disk('public')->delete($images[$imageIndex]);
            
            // Array'den kaldır
            unset($images[$imageIndex]);
            $images = array_values($images); // Reindex
            
            // Database'i güncelle
            $review->update(['images' => json_encode($images)]);
        }

        return back()->with('success', 'Fotoğraf silindi.');
    }

    // Public method - herkes hizmet sağlayıcının reviewlarını görebilir
    public function providerReviews(User $user)
    {
        if (!$user->isServiceProvider()) {
            abort(404);
        }

        $reviews = Review::where('reviewee_id', $user->id)
                        ->where('is_approved', true)
                        ->with(['booking.service', 'reviewer'])
                        ->latest()
                        ->paginate(10);

        return view('reviews.provider', compact('user', 'reviews'));
    }
}
