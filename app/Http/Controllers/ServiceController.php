<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['user', 'serviceCategory'])->active();

        if ($request->filled('category')) {
            $query->where('service_category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $services = $query->orderBy('is_featured', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);

        $categories = ServiceCategory::active()->ordered()->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function show($id)
    {
        $service = Service::with(['user', 'serviceCategory', 'bookings'])
            ->findOrFail($id);

        // Görüntülenme sayısını artır
        $service->incrementViews();

        // Benzer hizmetler
        $relatedServices = Service::with(['user', 'serviceCategory'])
            ->where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->take(4)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }

    public function category($slug)
    {
        $category = ServiceCategory::where('slug', $slug)->firstOrFail();
        
        $services = Service::with(['user', 'serviceCategory'])
            ->where('service_category_id', $category->id)
            ->active()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('services.category', compact('category', 'services'));
    }
}
