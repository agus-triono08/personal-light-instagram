<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman edit profil
    public function edit()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Kirim data pengguna ke view
        return view('profile.edit', compact('user'));
    }

    // Memperbarui profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Perbarui data pengguna
        $user->username = $validated['username'];
        $user->bio = $validated['bio'] ?? $user->bio;

        if ($request->hasFile('profile_picture')) {
            // Hapus gambar lama jika ada
            if ($user->profile_picture_url) {
                Storage::delete('public/' . $user->profile_picture_url);
            }
            // Simpan gambar baru
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture_url = $path;
        }

        $user->save();

        // Redirect ke home dengan pesan sukses
        return redirect()->route('home')->with('success', 'Profile updated successfully!');
    }
}
