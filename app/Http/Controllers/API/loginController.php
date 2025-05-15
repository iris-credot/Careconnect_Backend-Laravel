<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\NotFoundException;

class AuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new NotFoundException('Invalid email or password');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new NotFoundException('Invalid email or password');
        }

        $secret = env('JWT_SECRET');
        $tokenPayload = [
            'userId' => $user->_id,
            'username' => $user->username,
            'role' => $user->role,
            'exp' => time() + 3600
        ];

        $token = JWT::encode($tokenPayload, $secret, 'HS256');

        // Get profile info based on role
        $profile = null;
        if ($user->role === 'doctor') {
            $profile = Doctor::where('user_id', $user->_id)->with('user')->first();
        } elseif ($user->role === 'patient') {
            $profile = Patient::where('user_id', $user->_id)->with('user')->first();
        }

        $cookie = cookie('jwt', $token, 60 * 24, '/', null, true, true, false, 'Strict');

        return response()->json([
            'user' => $user,
            'profile' => $profile,
            'token' => $token
        ], 200)
        ->withCookie($cookie)
        ->header('Authorization', 'Bearer ' . $token);
    }

    // Logout function
    public function logout(Request $request)
    {
        $token = $request->cookie('jwt');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized: No token provided'], Response::HTTP_UNAUTHORIZED);
        }

        $secret = env('JWT_SECRET');

        try {
            JWT::decode($token, new Key($secret, 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized: Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        $cookie = Cookie::forget('jwt');

        return response()->json(['message' => 'Logged out successfully'])->withCookie($cookie);
    }
}
