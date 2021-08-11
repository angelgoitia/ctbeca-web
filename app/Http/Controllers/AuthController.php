<?php

namespace App\Http\Controllers;

use App\User;
use App\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);

        $user = User::where("email", request('email'))->first();

        if (!$user || !Hash::check(request('password'), $user->password)) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'Unauthorized',
            ], 401);
        } 

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addYear(5);
        $token->save();

        $players = Player::with('totalSLP')->get();

        return response()->json([
            'statusCode' => 201,
            'type'         => 0,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
            'players'       => $players,
            
        ]);
    }

    public function loginPlayer(Request $request)
    {
        $request->validate([
            'wallet'       => 'required|string|',
        ]);

        $credentials = request(['wallet']);
        $player = Player::where("wallet", request('wallet'))->with('totalSLP')->first();

        if (!$player) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }

        $tokenResult = $player->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addYear(5);
        $token->save();

        return response()->json([
            'statusCode' => 201,
            'type'         => 1,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $token->expires_at)
                    ->toDateTimeString(),
            'player'       => $player,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['statusCode' => 201, 'message' => 'Successfully logged out']);
    }

    public function admin(Request $request)
    {
        $players = Player::with('totalSLP')->with('animals')->get();

        return response()->json(
            [
                'statusCode' => 201,
                'admin' => $request->user(),
                'players' => $players,
            ]
        );
    }

    public function player(Request $request)
    {
        $player = Player::whereId($request->user()->id)->with('totalSLP')->with('animals')->first();

        return response()->json(['statusCode' => 201, 'player' => $player]);
    }
}
