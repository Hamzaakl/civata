<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(User $user = null)
    {
        $user = $user ?? Auth::user();
        
        // Sadece kendi profilini veya herkese açık profilleri görebilir
        if ($user->id !== Auth::id() && !$user->isServiceProvider()) {
            abort(404);
        }

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Profil fotoğrafı yükleme
        if ($request->hasFile('profile_photo')) {
            // Eski fotoğrafı sil
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        // Kullanıcı bilgilerini güncelle
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'bio' => $request->bio,
            'profile_photo' => $user->profile_photo,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Mevcut şifreyi kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifreniz yanlış.']);
        }

        // Yeni şifreyi kaydet
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Şifreniz başarıyla güncellendi!');
    }

    public function deleteProfilePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return back()->with('success', 'Profil fotoğrafınız silindi.');
    }

    public function toggleVerification(User $user)
    {
        // Sadece admin işlemi
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user->update(['is_verified' => !$user->is_verified]);

        return back()->with('success', 
            $user->is_verified ? 'Kullanıcı doğrulandı.' : 'Kullanıcı doğrulaması kaldırıldı.'
        );
    }
}
