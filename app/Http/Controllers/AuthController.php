<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            Log::info('Login attempt started');
            
            $credentials = $request->validated();
            Log::info('Credentials validated', ['username' => $credentials['username']]);
            
            $admin = Admin::where('username', $credentials['username'])->first();
            Log::info('Admin found', ['admin' => $admin ? 'yes' : 'no']);
            
            if (!$admin) {
                Log::warning('Admin not found');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admin not found',
                ], 401);
            }
            
            $passwordCheck = Hash::check($credentials['password'], $admin->password);
            Log::info('Password check', ['result' => $passwordCheck]);
            
            if (!$passwordCheck) {
                Log::warning('Invalid password');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid password',
                ], 401);
            }
            
            Log::info('About to create token');
            
            // Test tanpa delete tokens dulu
            $token = $admin->createToken('auth_token')->plainTextToken;
            Log::info('Token created successfully');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'admin' => [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'username' => $admin->username,
                        'phone' => $admin->phone,
                        'email' => $admin->email,
                    ],
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}