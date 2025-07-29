<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Bu alana sadece adminler erişebilir.');
            }
            return $next($request);
        }]);
    }

    public function dashboard()
    {
        // Genel İstatistikler
        $totalUsers = User::count();
        $totalCustomers = User::where('user_type', 'customer')->count();
        $totalProviders = User::where('user_type', 'service_provider')->count();
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', true)->count();
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalReviews = Review::count();
        $totalEarnings = Booking::where('status', 'completed')->sum('quoted_price');

        // Bu ay istatistikleri
        $thisMonth = Carbon::now()->startOfMonth();
        $newUsersThisMonth = User::where('created_at', '>=', $thisMonth)->count();
        $newServicesThisMonth = Service::where('created_at', '>=', $thisMonth)->count();
        $bookingsThisMonth = Booking::where('created_at', '>=', $thisMonth)->count();

        // Son aktiviteler
        $recentUsers = User::latest()->take(5)->get();
        $recentServices = Service::with(['user', 'serviceCategory'])->latest()->take(5)->get();
        $recentBookings = Booking::with(['customer', 'provider', 'service'])->latest()->take(5)->get();
        $pendingServices = Service::where('is_active', false)->count();

        // Kategori bazında istatistikler
        $categoryStats = ServiceCategory::withCount(['services', 'bookings'])
                                       ->orderBy('services_count', 'desc')
                                       ->get();

        // Aylık büyüme verisi (son 6 ay)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'services' => Service::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'bookings' => Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalCustomers', 'totalProviders', 'totalServices', 'activeServices',
            'totalBookings', 'completedBookings', 'totalReviews', 'totalEarnings',
            'newUsersThisMonth', 'newServicesThisMonth', 'bookingsThisMonth',
            'recentUsers', 'recentServices', 'recentBookings', 'pendingServices',
            'categoryStats', 'monthlyStats'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Filtreler
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->withCount(['services', 'customerBookings', 'providerBookings'])
                      ->latest()
                      ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleUserVerification(User $user)
    {
        $user->update(['is_verified' => !$user->is_verified]);
        
        $status = $user->is_verified ? 'doğrulandı' : 'doğrulanmamış duruma getirildi';
        return back()->with('success', "{$user->name} {$status}.");
    }

    public function toggleUserStatus(User $user)
    {
        // Admin kendini deaktive edemez
        if ($user->isAdmin() && $user->id === Auth::id()) {
            return back()->with('error', 'Kendi hesabınızı deaktive edemezsiniz.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'aktif' : 'deaktif';
        return back()->with('success', "{$user->name} {$status} duruma getirildi.");
    }

    public function categories()
    {
        $categories = ServiceCategory::withCount(['services', 'bookings'])
                                   ->orderBy('order_index')
                                   ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        ServiceCategory::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
            'order_index' => ServiceCategory::max('order_index') + 1,
        ]);

        return redirect()->route('admin.categories')
                       ->with('success', 'Kategori başarıyla eklendi!');
    }

    public function editCategory(ServiceCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, ServiceCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.categories')
                       ->with('success', 'Kategori başarıyla güncellendi!');
    }

    public function destroyCategory(ServiceCategory $category)
    {
        // Kategoriye ait hizmet varsa silinmez
        if ($category->services()->count() > 0) {
            return back()->with('error', 'Bu kategoriye ait hizmetler olduğu için silinemez.');
        }

        $category->delete();

        return redirect()->route('admin.categories')
                       ->with('success', 'Kategori başarıyla silindi!');
    }

    public function services(Request $request)
    {
        $query = Service::with(['user', 'serviceCategory'])->withCount('bookings');

        // Filtreler
        if ($request->filled('category_id')) {
            $query->where('service_category_id', $request->category_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $services = $query->latest()->paginate(15);
        $categories = ServiceCategory::active()->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function toggleServiceStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        $status = $service->is_active ? 'aktif' : 'pasif';
        return back()->with('success', "Hizmet {$status} duruma getirildi.");
    }

    public function toggleServiceFeatured(Service $service)
    {
        $service->update(['is_featured' => !$service->is_featured]);
        
        $status = $service->is_featured ? 'öne çıkan' : 'normal';
        return back()->with('success', "Hizmet {$status} duruma getirildi.");
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['customer', 'provider', 'service']);

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('preferred_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('preferred_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function reviews(Request $request)
    {
        $query = Review::with(['reviewer', 'reviewee', 'booking.service']);

        // Filtreler
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->is_approved);
        }

        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReviewApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        
        $status = $review->is_approved ? 'onaylandı' : 'onayı kaldırıldı';
        return back()->with('success', "Değerlendirme {$status}.");
    }

    public function analytics()
    {
        // Detaylı analitik veriler
        $analytics = [
            'user_growth' => $this->getUserGrowthData(),
            'booking_trends' => $this->getBookingTrendsData(),
            'category_performance' => $this->getCategoryPerformanceData(),
            'revenue_analytics' => $this->getRevenueAnalytics(),
            'top_providers' => $this->getTopProviders(),
            'geographic_distribution' => $this->getGeographicDistribution(),
        ];

        return view('admin.analytics', compact('analytics'));
    }

    private function getUserGrowthData()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'customers' => User::where('user_type', 'customer')
                                 ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                 ->count(),
                'providers' => User::where('user_type', 'service_provider')
                                 ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                 ->count(),
            ];
        }
        return $data;
    }

    private function getBookingTrendsData()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'total' => Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'completed' => Booking::where('status', 'completed')
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                    ->count(),
            ];
        }
        return $data;
    }

    private function getCategoryPerformanceData()
    {
        return ServiceCategory::withCount(['services', 'bookings'])
                             ->withSum('bookings', 'quoted_price')
                             ->orderBy('bookings_count', 'desc')
                             ->get();
    }

    private function getRevenueAnalytics()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => Booking::where('status', 'completed')
                                  ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                                  ->sum('quoted_price'),
            ];
        }
        return $data;
    }

    private function getTopProviders()
    {
        return User::where('user_type', 'service_provider')
                  ->withCount(['providerBookings' => function($query) {
                      $query->where('status', 'completed');
                  }])
                  ->withSum(['providerBookings' => function($query) {
                      $query->where('status', 'completed');
                  }], 'quoted_price')
                  ->orderBy('provider_bookings_count', 'desc')
                  ->take(10)
                  ->get();
    }

    private function getGeographicDistribution()
    {
        return User::selectRaw('city, COUNT(*) as user_count')
                  ->whereNotNull('city')
                  ->groupBy('city')
                  ->orderBy('user_count', 'desc')
                  ->take(10)
                  ->get();
    }
}
