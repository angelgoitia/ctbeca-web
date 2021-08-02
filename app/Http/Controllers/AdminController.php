<?php

namespace App\Http\Controllers;

use App\Player;
use Carbon\Carbon;
use App\Notifications\NewPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        Session::flash('message', "El correo o la contraseña es incorrecta!");
        return Redirect::back();
    }

    public function logout(Request $request)
    {
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }

        Auth::guard('web')->logout();
        $request->session()->flush();
        return redirect()->route('player.login');
    } 

    public function dashboard(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.dashboard'));
        }

        $totalSlpToday = 0;
        $totalSlpYesterday= 0;
        $totalSlpWeek = 0;

        $statusMenu = "dashboard";
        $idPlayer = 0;
        return view('admin.dashboard',compact("totalSlpToday", "totalSlpYesterday", "totalSlpWeek", "statusMenu", 'idPlayer', 'totalPendingUSD', 'totalPendingBS'));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();
        $count=1;

        for ($i = 0; $i < 7; $i++) {
            $totalSlp = 0;

            $listDay[$i]['dia'] = Carbon::now()->subDay(6-$i)->format('d');
            $listDay[$i]['totalSlp'] = $totalSlp;
        }

        $listDayJson = json_encode($listDay);
        echo json_encode($listDay);
    }

    public function listPlayers(Request $request)
    {  
        $playersAll= Player::orderBy("name","asc")->get();

        $statusMenu = "players";
        return view('admin.listPlayers', compact('statusMenu', 'playersAll'));
    }

    public function formPlayer(Request $request)
    {
        $file = $request->file('codeQr');

        $player =  Player::updateOrCreate(
            [
                'wallet' => str_replace("ronin:","", $request->wallet)
            ],
            [
                'name'      => $request->name,
                'phone'     => $request->digPhone."-".$request->phone,
                'telegram'  => $request->telegram,
                'email'     => $request->email,
                'reference' => $request->reference,
                'emailGame' => $request->emailGame,
                'passwordGame'  => bcrypt($request->password),
            ]
        );
        
        if(!empty($file) && !empty($request->urlPrevius)){
            $urlPrevius = substr($request->urlPrevious,8);
            \Storage::disk('public')->delete($urlPrevius);
        }

        $urlCodeQr = 'players/'.$player->id.'/codeQr-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
        \Storage::disk('public')->put($urlCodeQr, file_get_contents($request->file('codeQr'))); 

        $player->urlCodeQr = $urlCodeQr;
        $player->save();

        (new Player)->forceFill([
            'email' => $request->email,
        ])->notify(
            new NewPlayer($player, $request->password)
        );  

        return redirect()->route('admin.listPlayers');
    }

    public function emailPreview ()
    {
        $player = Player::whereId(1)->first();
        $message = (new NewPlayer($player, 'prueba01'))->toMail('test@test.com');
            return $message->render();
    }
}
