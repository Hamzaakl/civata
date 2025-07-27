<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredServices = Service::with(['user', 'serviceCategory'])
            ->active()
            ->featured()
            ->take(6)
            ->get();

        $categories = ServiceCategory::active()
            ->ordered()
            ->take(8)
            ->get();

        $topProviders = User::where('user_type', 'service_provider')
            ->where('is_verified', true)
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('featuredServices', 'categories', 'topProviders'));
    }

    public function search(Request $request)
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

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        $services = $query->paginate(12);
        $categories = ServiceCategory::active()->ordered()->get();

        return view('services.index', compact('services', 'categories'));
    }
}
