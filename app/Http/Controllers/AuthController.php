<?php

namespace App\Http\Controllers;

use App\User;
use App\Player;
use Carbon\Carbon;
use DB;
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
        $player = Player::where("emailGame", request('email'))->with('group')->with(['totalSLP' => function($q) use($now) {
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
            $admins = User::where('id', '!=', 1)->where('id', '!=', $user->id)->get();
            /* Update SLP Daily */
            if($user->id == 1)
                $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
                    $q->where('date', "!=", $now)->orderBy('date','DESC'); 
                }])->get();
            else
                $players = Player::where('admin_id', $user->id)->with('group')->with(['totalSLP' => function($q) use($now) {
                    $q->where('date', "!=", $now)->orderBy('date','DESC'); 
                }])->get();

            app('App\Http\Controllers\Controller')->updateSlpPlayers($players);
            
            if($user->id == 1)
                $players = Player::with('totalSLP')->with('animals')->with('claimsApi')->with('group')->get();
            else
                $players = Player::where('admin_id', $user->id)->with('totalSLP')->with('animals')->with('claimsApi')->with('group')->get();
                

            return response()->json([
                'statusCode' => 201,
                'type'         => 0,
                'admin'        => $user,
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at)
                        ->toDateTimeString(),
                'admins'       => $admins,
                'players'       => $players,
                
            ]);
        }else{
            /* Update SLP Daily */
            app('App\Http\Controllers\Controller')->updateSlp($player);
            $player = Player::where("emailGame", request('email'))->with('totalSLP')->with('animals')->with('claimsApi')->with('claimsApi')->with('group')->first();
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
        $admins = User::where('id', '!=', 1)->where('id', '!=', $request->user()->id)->get();
        $now = Carbon::now()->format('Y-m-d');

        /* Update SLP Daily */
        if($request->user()->id == 1)
            $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
                $q->where('date', "!=", $now)->orderBy('date','DESC'); 
            }])->get();
        else
            $players = Player::where('admin_id', $request->user()->id)->with('group')->with(['totalSLP' => function($q) use($now) {
                $q->where('date', "!=", $now)->orderBy('date','DESC'); 
            }])->get();

        app('App\Http\Controllers\Controller')->updateSlpPlayers($players); 

        if($request->user()->id == 1)
            $players = Player::with('totalSLP')->with('animals')->with('claimsApi')->with('group')->get();
        else
            $players = Player::where('admin_id', $request->user()->id)->with('totalSLP')->with('animals')->with('claimsApi')->with('group')->get();
            

        return response()->json(
            [
                'statusCode' => 201,
                'admin' => $request->user(),
                'admins' => $admins,
                'players' => $players,
            ]
        );
    }

    public function player(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        /* Update SLP Daily */
        $player = Player::whereId($request->user()->id)->with('group')->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->first();

        app('App\Http\Controllers\Controller')->updateSlp($player);
        
        $player = Player::whereId($request->user()->id)->with('totalSLP')->with('animals')->with('claimsApi')->with('group')->first();

        return response()->json(['statusCode' => 201, 'player' => $player]);
    }

    public function updateToken(Request $request){
        if($request->type == 0){
            $admin = User::whereId($request->user()->id)->first();
            $admin->tokenFCM = $request->tokenFCM;
            $admin->save();
        }else{
            $player = Player::whereId($request->user()->id)->first();
            $player->tokenFCM = $request->tokenFCM;
            $player->save();
        }

        return response()->json(['statusCode' => 201]);

    }

    public function versionApp(Request $request)
    {
        $version = DB::table("version")->whereId(1)->first();
        return response()->json([
            'statusCode' => 201,
            'data' => $version,
        ]);
    }
}
