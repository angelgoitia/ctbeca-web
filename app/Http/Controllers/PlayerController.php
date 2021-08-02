<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PlayerController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'wallet' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt(['wallet' => $request->wallet,])) {
            $user = User::whereId(Auth::guard('web')->id())->first();

            if($user->status != 0){
                Auth::guard('web')->logout();
                $request->session()->flush();
            }else
                return redirect()->intended(route('player.dashboard'));
        }

        Session::flash('message', "El correo o la contraseña es incorrecta!");
        return Redirect::back();
    }
}
