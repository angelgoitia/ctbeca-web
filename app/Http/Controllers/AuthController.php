<?php

namespace App\Http\Controllers;

use App\User;
use App\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();
        return response()->json([
            'type'         => 0,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
            
        ]);
    }

    public function loginPlayer(Request $request)
    {
        $request->validate([
            'wallet'       => 'required|string|',
        ]);

        $credentials = request(['wallet']);
        $player = Player::where("wallet", request('wallet'))->first();

        if (!$player) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }

        $tokenResult = $player->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();
        return response()->json([
            'type'         => 1,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function player(Request $request)
    {
        $player = Player::where("wallet", request('wallet'))->first();

        return response()->json(['statusCode' => 201,'data' => $request->user()]);
    }
}
