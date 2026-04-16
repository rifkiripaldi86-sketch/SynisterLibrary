<?php

namespace App\Http\Controllers;

// FILE: app/Http/Controllers/AuthController.php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // =============================================
    // SHOW FORMS
    // =============================================

    /**
     * Tampilkan halaman login.
     * Jika sudah login, redirect ke dashboard sesuai role.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /**
     * Tampilkan halaman register (untuk siswa baru).
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    // =============================================
    // PROSES LOGIN
    // =============================================

    /**
     * Proses login — validasi username & password.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba login dengan username (bukan email)
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role
            return $this->redirectByRole()
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        // Login gagal
        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau password salah. Silakan coba lagi.');
    }

    // =============================================
    // PROSES REGISTER
    // =============================================

    /**
     * Proses registrasi siswa baru.
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'      => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users,username',
            'no_induk'  => 'required|string|max:20|unique:users,no_induk',
            'kelas'     => 'required|string|max:30',
            'password'  => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan, pilih yang lain.',
            'no_induk.required'      => 'Nomor induk siswa (NIS) wajib diisi.',
            'no_induk.unique'        => 'NIS sudah terdaftar. Hubungi admin jika ada masalah.',
            'kelas.required'         => 'Kelas wajib diisi.',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'password.min'           => 'Password minimal 6 karakter.',
        ]);

        // Buat akun siswa baru
        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'no_induk'  => $request->no_induk,
            'kelas'     => $request->kelas,
            'password'  => Hash::make($request->password),
            'role'      => 'siswa', // Registrasi publik selalu jadi siswa
        ]);

        // Auto login setelah register
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->name . '!');
    }

    // =============================================
    // LOGOUT
    // =============================================

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar.');
    }

    // =============================================
    // HELPER PRIVATE
    // =============================================

    /**
     * Redirect ke dashboard berdasarkan role user yang sedang login.
     */
    private function redirectByRole()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }
}