<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Service $service)
    {
        return view('bookings.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'nullable|date_format:H:i',
        ]);

        $booking = Booking::create([
            'service_id' => $service->id,
            'customer_id' => Auth::id(),
            'provider_id' => $service->user_id,
            'description' => $request->description,
            'address' => $request->address,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Rezervasyon talebiniz başarıyla gönderildi!');
    }

    public function show(Booking $booking)
    {
        // Sadece rezervasyonun tarafları görebilir
        if (!in_array(Auth::id(), [$booking->customer_id, $booking->provider_id])) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isServiceProvider()) {
            $bookings = $user->providerBookings()->with(['service', 'customer'])->latest()->get();
        } else {
            $bookings = $user->customerBookings()->with(['service', 'provider'])->latest()->get();
        }

        return view('bookings.index', compact('bookings'));
    }

    public function accept(Booking $booking, Request $request)
    {
        // Sadece hizmet sağlayıcı kabul edebilir
        if (Auth::id() !== $booking->provider_id) {
            abort(403);
        }

        $request->validate([
            'quoted_price' => 'nullable|numeric|min:0',
            'provider_notes' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => 'accepted',
            'quoted_price' => $request->quoted_price,
            'provider_notes' => $request->provider_notes,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Rezervasyon kabul edildi!');
    }

    public function reject(Booking $booking, Request $request)
    {
        // Sadece hizmet sağlayıcı reddedebilir
        if (Auth::id() !== $booking->provider_id) {
            abort(403);
        }

        $request->validate([
            'provider_notes' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'rejected',
            'provider_notes' => $request->provider_notes,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Rezervasyon reddedildi.');
    }

    public function complete(Booking $booking)
    {
        // Sadece hizmet sağlayıcı tamamlayabilir
        if (Auth::id() !== $booking->provider_id) {
            abort(403);
        }

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Hizmet tamamlandı olarak işaretlendi!');
    }

    public function cancel(Booking $booking)
    {
        // Müşteri veya sağlayıcı iptal edebilir
        if (!in_array(Auth::id(), [$booking->customer_id, $booking->provider_id])) {
            abort(403);
        }

        // Sadece pending veya accepted durumunda iptal edilebilir
        if (!in_array($booking->status, ['pending', 'accepted'])) {
            return back()->with('error', 'Bu rezervasyon iptal edilemez.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Rezervasyon iptal edildi.');
    }
}
