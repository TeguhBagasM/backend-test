<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            // Check if user is already authenticated
            if (Auth::guard('admin')->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah login',
                ], 400);
            }

            $credentials = $request->only('username', 'password');

            $admin = Admin::where('username', $credentials['username'])->first();

            if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Username atau password salah',
                ], 401);
            }

            $token = $admin->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'data' => [
                    'token' => $token,
                    'admin' => [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'username' => $admin->username,
                        'phone' => $admin->phone,
                        'email' => $admin->email,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout berhasil',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }
}