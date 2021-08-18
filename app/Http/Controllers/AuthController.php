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
    public function login(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        $user = User::where("email", request('email'))->first();
        $player = Player::where("emailGame", request('email'))->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->first();

        if (!(!$user || !Hash::check(request('password'), $user->password)) xor (!$player || !Hash::check(request('password'), $player->passwordGame))) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'Unauthorized',
            ], 401);
        } 

        if($user)
            $tokenResult = $user->createToken('Personal Access Token');
        else
            $tokenResult = $player->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addYear(5);
        $token->save();

        if($user){
            $players = Player::with(['totalSLP' => function($q) use($now) {
                $q->where('date', "!=", $now)->orderBy('date','DESC'); 
            }])->get();

            app('App\Http\Controllers\Controller')->updateSlpPlayers($players);

            $players = Player::with('totalSLP')->with('animals')->get();

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
        }else{
            app('App\Http\Controllers\Controller')->updateSlp($player);
            $player = Player::where("emailGame", request('email'))->with('totalSLP')->with('animals')->first();
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
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['statusCode' => 201, 'message' => 'Successfully logged out']);
    }

    public function admin(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $players = Player::with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->get();

        app('App\Http\Controllers\Controller')->updateSlpPlayers($players);

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
        $now = Carbon::now()->format('Y-m-d');
        $player = Player::whereId($request->user()->id)->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->first();
        app('App\Http\Controllers\Controller')->updateSlp($player);
        $player = Player::whereId($request->user()->id)->with('totalSLP')->with('animals')->first();

        return response()->json(['statusCode' => 201, 'player' => $player]);
    }
}
