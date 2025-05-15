<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Exceptions\BadRequestError;
use App\Exceptions\NotFoundError;
use App\Exceptions\UnauthorizedError;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Exception;

class UserController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    /**
     * Upload file to Cloudinary and return secure URL
     */
    private function uploadToCloudinary($file)
    {
        if (!$file->isValid()) {
            throw new BadRequestError('Invalid image file');
        }

        $publicId = 'CareConnect/' . Str::uuid();

        $uploadResult = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'public_id' => $publicId,
            'folder' => 'CareConnect',
            'resource_type' => 'image',
        ]);

        $url = $uploadResult['secure_url'] ?? null;

        if (!$url) {
            throw new Exception('Cloudinary upload failed');
        }

        return $url;
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'names' => 'nullable|string',
            'profile' => 'nullable|string',
            'address' => 'nullable|string',
            'phoneNumber' => 'nullable|string',
            'dateOfBirth' => 'nullable|date',
            'password' => 'required|string|min:8',
            'gender' => 'required|string',
            'image' => 'required|image', 
        ]);

        $email = strtolower($request->email);

        if (User::where('email', $email)->exists()) {
            throw new BadRequestError('Email already in use');
        }

        // Generate OTP and expiration date (5 minutes)
        $otp = random_int(100000, 999999);
        $otpExpiration = Carbon::now()->addMinutes(5);

        try {
            $imageUrl = $this->uploadToCloudinary($request->file('image'));

            $user = User::create([
                'username' => $request->username,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'names' => $request->names,
                'image' => $imageUrl,
                'bio' => $request->profile,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'dateOfBirth' => $request->dateOfBirth,
                'email' => $email,
                'password' => $request->password, 
                'gender' => $request->gender,
                'otp' => $otp,
                'otpExpires' => $otpExpiration,
                'verified' => false,
            ]);

            $emailBody = "Your OTP is: {$otp}";
            EmailService::sendEmail($email, "Care-Connect System: Verify your account", $emailBody);

            return response()->json(['user' => $user, 'otp' => $otp], 201);

        } catch (Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            throw new BadRequestError('Failed to create user: ' . $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|integer',
        ]);

        $user = User::where('otp', $request->otp)->first();

        if (!$user) {
            throw new UnauthorizedError('Authorization denied');
        }

        if (Carbon::now()->greaterThan($user->otpExpires)) {
            throw new UnauthorizedError('OTP expired');
        }

        $user->verified = true;
        $user->otp = null;
        $user->otpExpires = null;
        $user->save();

        return response()->json([
            'message' => 'User account verified!',
            'user' => $user,
        ], 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new NotFoundError('User not found');
        }
        $user->delete();

        return response()->json(['user' => $user], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!$user) {
            throw new NotFoundError('User not found');
        }

        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json(['error' => 'Incorrect current password'], 400);
        }

        $user->password = $request->newPassword;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password updated successfully'], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new NotFoundError('User not found');
        }

        $user->fill($request->except(['password', 'otp', 'otpExpires', 'verified']));
        $user->save();

        return response()->json($user, 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new NotFoundError('Your email is not registered');
        }

        $token = Str::random(64);
        $expiration = Carbon::now()->addMinutes(15);

        Otp::updateOrCreate(
            ['user_id' => $user->_id],
            ['token' => $token, 'expirationDate' => $expiration]
        );

        $link = url("/auth/reset?token={$token}&id={$user->_id}");
        $emailBody = "Click on the link below to reset your password:\n\n{$link}";

        EmailService::sendEmail($request->email, "Reset your password", $emailBody);

        return response()->json([
            'message' => 'We sent you a reset password link on your email!',
            'link' => $link,
        ], 200);
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otpRecord = Otp::where('user_id', $user->_id)->where('token', $token)->first();
        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expirationDate)) {
            return response()->json(['message' => 'Token has expired'], 400);
        }

        $user->password = $request->newPassword;
        $user->save();

        $otpRecord->delete();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
