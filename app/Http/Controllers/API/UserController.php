<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Registration Successful. Please Login.'], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Login failed] Message: ' . $th->getMessage());
            return response()->json([
                'error' => 'Registration failed.',
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'This email is not registered'], 401);
            }

            if ($user->suspend_flag) {
                return response()->json(['error' => 'This account is suspended'], 403);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Password is incorrect'], 401);
            }

            // Store Device Token
            $deviceToken = $request->device_token;
            if ($deviceToken) {
                $user->device_token =  $deviceToken;
                $user->save();
            }

            // Delete Old Access Token
            if ($user->tokens->count() >= 1) {
                $user->tokens()->delete();
            }
            // Generate New Access Token
            $token = $user->createToken($request->email)->plainTextToken;

            $resData =  [
                'token' => $token,
                'user' => $user->only('id', 'name', 'email'),
            ];
            return response()->json([
                'message' => 'Login Successful.',
                'data' => $resData
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Login failed] Message: ' . $th->getMessage());
            return response()->json([
                'error' => 'Login failed.' . $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged Out Successful'], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Logout failed] Message: ' . $th->getMessage());
            return response()->json([
                'error' => 'Logout failed.' . $th->getMessage(),
            ], 500);
        }
    }

    public function checkToken()
    {
        if (auth('sanctum')->check()) {
            // Token is valid, you can handle the user data here
            return response()->json([
                'message' => 'Token is valid',
                'user' => auth('sanctum')->user(),
            ]);
        } else {
            // Token is not valid
            return response()->json(['message' => 'Token is not valid'], 401);
        }
    }
}
