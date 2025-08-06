<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // User 모델을 사용해야 합니다.
use Illuminate\Http\Request;

class TokenController extends Controller
{
    // 이메일을 전달받아 토큰을 생성합니다.
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // 기존 토큰을 모두 삭제하고 새 토큰 발급
        $user->tokens()->delete();
        $token = $user->createToken('test-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Token generated successfully.'
        ]);
    }

    // 사용자 ID를 전달받아 토큰을 가져옵니다.
    public function getToken(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id'
        ]);

        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // 가장 최근에 발급된 토큰을 가져옵니다.
        $token = $user->tokens()->latest()->first();

        if (!$token) {
            return response()->json(['message' => 'No token found for this user'], 404);
        }

        return response()->json([
            'token' => $token->token,
            'message' => 'Token retrieved successfully.'
        ]);
    }
}