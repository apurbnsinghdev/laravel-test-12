<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // Token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
