<?php

namespace App\Http\Controllers;

use Session;
use App\Player;
use App\TotalSlp;
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
        $playerSelect = array();

        $statusMenu = "players";
        return view('admin.listPlayers', compact('statusMenu', 'playersAll', 'playerSelect'));
    }

    public function formPlayer(Request $request)
    {
        
        if(Player::where('wallet',  str_replace("ronin:","", $request->wallet))->first())
        {
            Session::flash('message', "Billetera ingresado ya se encuentra registrado en el sistema");
            return Redirect::back();
        }
        else if(Player::where('email', $request->email)->first())
        {
            Session::flash('message', "Correo Electrónico del jugador ya se encuentra registrado en el sistema");
            return Redirect::back();
        }
        else if(Player::where('emailGame', $request->emailGame)->first())
        {
            Session::flash('message', "Correo Electrónico del Axie infinity ya se encuentra registrado en el sistema!");
            return Redirect::back();
        }

        $file = $request->file('codeQr');

        if(empty($request->playerSelect))
        {
            $player =  Player::create(
                [
                    'name'          => $request->name,
                    'phone'         => $request->digPhone."-".$request->phone,
                    'telegram'      => $request->telegram,
                    'email'         => $request->email,
                    'reference'     => $request->reference,
                    'emailGame'     => $request->emailGame,
                    'passwordGame'  => bcrypt($request->passwordGame),
                    'wallet'        => str_replace("ronin:","", $request->wallet)
                ]
            );
        }else{
            $player =  Player::where('wallet', str_replace("ronin:","", $request->wallet))->first();
            $player->name          = $request->name;
            $player->phone         = $request->digPhone."-".$request->phone;
            $player->telegram      = $request->telegram;
            $player->email         = $request->email;
            $player->reference     = $request->reference;
            $player->emailGame     = $request->emailGame;
            $player->passwordGame  = bcrypt($request->passwordGame);
            $player->save();
        }
        
        if(!empty($file) && !empty($request->urlPrevius)){
            \Storage::disk('public')->delete($request->urlPrevius);
        }
        
        if(!empty($file)){
            $urlCodeQr = 'players/'.$player->id.'/codeQr-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
            \Storage::disk('public')->put($urlCodeQr, file_get_contents($request->file('codeQr'))); 
            $player->urlCodeQr = $urlCodeQr;
            $player->save();
        }

        (new Player)->forceFill([
            'email' => $request->email,
        ])->notify(
            new NewPlayer($player, $request->passwordGame)
        );  

        return redirect()->route('admin.listPlayers');
    }

    public function showPlayer(Request $request)
    {
        $playerSelect = Player::whereId($request->id)->first();

        $returnHTML=view('admin.modal.player', compact('playerSelect'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function listDaily(Request $request)
    {  
        $startDate = Carbon::now()->setDay(1)->subMonth(4)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        if($request->all()){
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
        }

        $playersAll = Player::with(['totalSLP' => function($q) use($startDate, $endDate) {
            $q->whereDate('created_at', ">=",$startDate)
                ->whereDate('created_at', "<=",$endDate); 
        }])->get();

        $statusMenu = "gameHistory";
        return view('admin.listDaily', compact('statusMenu', 'startDate', 'endDate', 'playersAll'));
    }


    public function apiSLP(){
        $players = Player::with('lastSLP')->get();
    
        foreach ($players as $player)
        {
            /* dd($player->lastSLP->total); */
            /* TotalSlp::create([
                'player_id' => $player->id,
                'total' => 50,
                'daily' => 40,
            ]);  */

            $url = "https://api.lunaciarover.com/stats/0x".$player->wallet;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            
            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch); 
            
            $dailyYesterday = empty($player->lastSLP)? 0 : $player->lastSLP->total;
            $totaldaily = intval($resultApi['total_slp']) - intval($resultApi['total_slp']);
            
            TotalSlp::create([
                'player_id' => $player->id,
                'total' => intval($resultApi['total_slp']),
                'daily' => $totaldaily,
            ]);
        }
    }

}
