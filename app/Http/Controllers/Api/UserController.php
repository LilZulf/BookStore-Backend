<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    use PasswordValidationRules;

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Get user data');
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Validator Error',
                    'errors' => $validator->errors()
                ]);
            }

            // Attempt to authenticate user
            if (!Auth::attempt($request->only('email', 'password'))) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 401);
            }

            // Retrieve authenticated user
            $user = Auth::user();


            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Credentials');
            }


            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage()
            ]);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'email|required|max:255|unique:users',
                'password' => $this->passwordRules()
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Validator Error',
                    'error' => $validator->errors()
                ]);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'password' => Hash::make($request->password)
            ]);
            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Registration Success');
        } catch (Exception $error) {
            //throw $th;
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success([
            'token' => $token,
        ], 'Token Revoked');
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:users',
            'password' => $this->passwordRules()
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validator Error',
                'error' => $validator->errors()
            ]);
        }

        $data = $request->all();

        $user = Auth::user();
        $user->update($data);
        return ResponseFormatter::success($user, 'User Updated');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validator Error',
                'error' => $validator->errors()
            ], 'Update Photo Fails', 401);
        }

        if($request->file('file'))
        {
            $file = $request->file->store('assets/user', 'public');
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success($user, 'File Successfully uploaded');
        }

    }

}
