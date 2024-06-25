<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'data' => $user,
        ]);
    }

    public function registerPenjual(Request $request)
    {
        return new ResponseResource(false, "Gagal Registrasi", null);
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return new ResponseResource(false, "Gagal Login", null);
        }

        $penjual = Penjual::where('user_id', $user->id)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return new ResponseResource(true, "Berhasil Login", [
            'user' => $user,
            'penjual' => $penjual,
            "token_type" => "Bearer",
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return new ResponseResource(true, "Berhasil Logout", null);
    }
}
