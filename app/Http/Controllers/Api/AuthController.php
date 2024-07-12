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
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        try {
            // nama username email password notelp
            $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8'
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return new ResponseResource(true, "Berhasil Registrasi Pembeli", $user);
    }

    public function registerPenjual(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:50',
                'username' => 'required|string|max:20',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'no_telp' => 'required|string|max:20',
                'alamat' => 'required|string',
                'foto' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
                'ewallet_id' => 'required|exists:ewallets,id',
                'nama_profil_ewallet' => 'required|string|max:20',
                'nomor_ewallet' => 'required|string|max:20',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        $user = User::create([
            'role' => 'penjual',
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->hasFile('foto')) {
            $filePath = $request->file('foto')->store('images/foto-penjual');
            $validated['foto'] = $filePath;
        }

        $penjual = Penjual::create([
            'user_id' => $user->id,
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'foto' => $validated['foto'],
            'ewallet_id' => $validated['ewallet_id'],
            'nama_profil_ewallet' => $validated['nama_profil_ewallet'],
            'nomor_ewallet' => $validated['nomor_ewallet'],

        ]);

        return new ResponseResource(true, "Berhasil Registrasi Penjual", $penjual);
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
