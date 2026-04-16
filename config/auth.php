<?php

// FILE: config/auth.php
// PENTING: Ubah 'username' field untuk login (default Laravel pakai 'email')

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];

/*
|--------------------------------------------------------------------------
| CATATAN PENTING — Login dengan Username, bukan Email
|--------------------------------------------------------------------------
|
| Laravel secara default login pakai 'email'.
| Karena kita pakai 'username', tambahkan method ini di model User.php:
|
|   public function getAuthPassword()
|   {
|       return $this->password;
|   }
|
| Dan di AuthController, kita sudah handle ini dengan:
|   Auth::attempt(['username' => $request->username, 'password' => $request->password])
|
| Tidak perlu mengubah file ini lebih lanjut.
|
*/