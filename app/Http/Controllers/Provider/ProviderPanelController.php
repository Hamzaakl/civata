<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProviderPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', function ($request, $next) {
            if (!Auth::user()->isServiceProvider()) {
                abort(403, 'Bu alana sadece hizmet sağlayıcılar erişebilir.');
            }
            return $next($request);
        }]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // İstatistikler
        $totalServices = $user->services()->count();
        $activeServices = $user->services()->active()->count();
        $totalBookings = $user->providerBookings()->count();
        $pendingBookings = $user->providerBookings()->pending()->count();
        $completedBookings = $user->providerBookings()->completed()->count();
        $totalEarnings = $user->providerBookings()->completed()->sum('quoted_price');
        $averageRating = $user->rating;
        $totalReviews = $user->total_reviews;

        // Bu ay ve geçen ay karşılaştırmaları
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $thisMonthBookings = $user->providerBookings()->where('created_at', '>=', $thisMonth)->count();
        $lastMonthBookings = $user->providerBookings()->where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count();
        
        $thisMonthEarnings = $user->providerBookings()->completed()->where('updated_at', '>=', $thisMonth)->sum('quoted_price');
        $lastMonthEarnings = $user->providerBookings()->completed()->where('updated_at', '>=', $lastMonth)->where('updated_at', '<', $thisMonth)->sum('quoted_price');

        // Son rezervasyonlar
        $recentBookings = $user->providerBookings()
                              ->with(['service', 'customer'])
                              ->latest()
                              ->take(5)
                              ->get();

        // Son değerlendirmeler
        $recentReviews = Review::where('reviewee_id', $user->id)
                              ->with(['reviewer', 'booking.service'])
                              ->latest()
                              ->take(5)
                              ->get();

        // En performanslı hizmetler
        $topServices = $user->services()
                           ->withCount('bookings')
                           ->orderBy('bookings_count', 'desc')
                           ->take(5)
                           ->get();

        return view('provider.dashboard', compact(
            'totalServices', 'activeServices', 'totalBookings', 'pendingBookings', 
            'completedBookings', 'totalEarnings', 'averageRating', 'totalReviews',
            'thisMonthBookings', 'lastMonthBookings', 'thisMonthEarnings', 'lastMonthEarnings',
            'recentBookings', 'recentReviews', 'topServices'
        ));
    }

    public function services()
    {
        $user = Auth::user();
        $services = $user->services()
                        ->with(['serviceCategory'])
                        ->withCount('bookings')
                        ->latest()
                        ->paginate(10);

        return view('provider.services.index', compact('services'));
    }

    public function createService()
    {
        $categories = ServiceCategory::active()->ordered()->get();
        return view('provider.services.create', compact('categories'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'description' => 'required|string|max:2000',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'price_type' => 'required|in:fixed,hourly,negotiable',
            'service_area' => 'required|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
        ]);

        // Fotoğrafları yükle
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('service-images', 'public');
                $images[] = $path;
            }
        }

        Service::create([
            'user_id' => Auth::id(),
            'service_category_id' => $request->service_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'price_type' => $request->price_type,
            'service_area' => $request->service_area,
            'images' => json_encode($images),
            'is_active' => true,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('provider.services')
                       ->with('success', 'Hizmet başarıyla eklendi!');
    }

    public function editService(Service $service)
    {
        // Sadece kendi hizmetini düzenleyebilir
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = ServiceCategory::active()->ordered()->get();
        return view('provider.services.edit', compact('service', 'categories'));
    }

    public function updateService(Request $request, Service $service)
    {
        // Sadece kendi hizmetini düzenleyebilir
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'description' => 'required|string|max:2000',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'price_type' => 'required|in:fixed,hourly,negotiable',
            'service_area' => 'required|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Yeni fotoğrafları ekle
        $images = json_decode($service->images, true) ?? [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('service-images', 'public');
                $images[] = $path;
            }
        }

        $service->update([
            'service_category_id' => $request->service_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'price_type' => $request->price_type,
            'service_area' => $request->service_area,
            'images' => json_encode($images),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('provider.services')
                       ->with('success', 'Hizmet başarıyla güncellendi!');
    }

    public function destroyService(Service $service)
    {
        // Sadece kendi hizmetini silebilir
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        // Aktif rezervasyonu varsa silinmez
        $activeBookings = $service->bookings()->whereIn('status', ['pending', 'accepted'])->count();
        if ($activeBookings > 0) {
            return back()->with('error', 'Aktif rezervasyonu olan hizmetler silinemez.');
        }

        // Fotoğrafları sil
        $images = json_decode($service->images, true) ?? [];
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }

        $service->delete();

        return redirect()->route('provider.services')
                       ->with('success', 'Hizmet başarıyla silindi!');
    }

    public function deleteServiceImage(Service $service, $imageIndex)
    {
        // Sadece kendi hizmetinin resmini silebilir
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $images = json_decode($service->images, true) ?? [];
        
        if (isset($images[$imageIndex])) {
            // Dosyayı sil
            Storage::disk('public')->delete($images[$imageIndex]);
            
            // Array'den kaldır
            unset($images[$imageIndex]);
            $images = array_values($images); // Reindex
            
            // Database'i güncelle
            $service->update(['images' => json_encode($images)]);
        }

        return back()->with('success', 'Fotoğraf silindi.');
    }

    public function toggleServiceStatus(Service $service)
    {
        // Sadece kendi hizmetinin durumunu değiştirebilir
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'aktif' : 'pasif';
        return back()->with('success', "Hizmet {$status} duruma getirildi.");
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $query = $user->providerBookings()->with(['service', 'customer']);

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $bookings = $query->latest()->paginate(10);
        $services = $user->services()->get();

        return view('provider.bookings', compact('bookings', 'services'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $reviews = Review::where('reviewee_id', $user->id)
                        ->with(['reviewer', 'booking.service'])
                        ->latest()
                        ->paginate(10);

        return view('provider.reviews', compact('reviews'));
    }

    public function analytics()
    {
        $user = Auth::user();
        
        // Son 12 ayın verileri
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $bookings = $user->providerBookings()
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                            ->count();
                            
            $earnings = $user->providerBookings()
                            ->completed()
                            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                            ->sum('quoted_price');
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'bookings' => $bookings,
                'earnings' => $earnings
            ];
        }

        // Kategori bazında performans
        $categoryStats = $user->services()
                             ->join('service_categories', 'services.service_category_id', '=', 'service_categories.id')
                             ->join('bookings', 'services.id', '=', 'bookings.service_id')
                             ->selectRaw('service_categories.name, COUNT(bookings.id) as booking_count, SUM(CASE WHEN bookings.status = "completed" THEN bookings.quoted_price ELSE 0 END) as total_earnings')
                             ->groupBy('service_categories.id', 'service_categories.name')
                             ->orderBy('booking_count', 'desc')
                             ->get();

        return view('provider.analytics', compact('monthlyData', 'categoryStats'));
    }
}
