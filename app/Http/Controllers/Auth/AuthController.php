<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller {

    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::select('id', 'username', 'password')
                ->where('username', $request->username)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'logged_in_error' => 'Check your username or password'
                ], 401);
            }

            return $this->createToken($user, "fieldlanka");

        }catch(\Exception $error){
            return response()->json([
                'status' => 500,
                'message' => "Back-end error",
                'errors' => $error
            ], 500);
        }
    }

    public function logout() {
        try {

            $user = request()->user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            return response()->json([
                'status' => 200,
                'message' => "Logged out",
            ]);

        }catch(\Exception $error){
            return response()->json([
                'status' => 500,
                'message' => "Back-end error",
                'errors' => $error
            ], 500);
        }
    }

    public function signInWithUser() {
        try {
            if ($admin = Auth::user()) {

                return response()->json([
                    'status' => 200,
                    'message' => 'Authorized.',
                    'role' => 'admin',
                    'user' => $admin->username,
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => "Back-end error",
                'errors' => $error
            ], 500);
        }
    }

    private function createToken ($user, $device_name) {
        $token =  $user->createToken($device_name)->plainTextToken;

        return response()->json([
            'status' => 200,
            'role' => 'admin',
            'user' => $user->username,
            'accessToken' => $token
        ]);
    }
}
