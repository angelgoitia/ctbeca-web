<?php

namespace App\Http\Controllers;

use Session;
use App\Animal;
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
        $startDate = Carbon::now()->setDay(1)->subMonth(2)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');
        $yesteday = Carbon::now()->subDays(1)->format('Y-m-d');
        $startWeek = Carbon::now()->subDays(6)->format('Y-m-d');

        $playersAll = Player::with(['totalSLP' => function($q) use($startWeek, $now) {
            $q->whereDate('date', ">=",$startWeek)
                ->whereDate('date', "<=",$now); 
        }])->get();

        foreach($playersAll as $player){

            foreach($player->totalSLP as $slp){

                if($now == Carbon::parse($slp->date)->format('Y-m-d'))
                    $totalSlpToday += $slp->daily;

                if($yesteday == Carbon::parse($slp->date)->format('Y-m-d'))
                    $totalSlpYesterday += $slp-> daily;

                if(Carbon::parse($slp->date)->format('Y-m-d') >= $startWeek && Carbon::parse($slp->date)->format('Y-m-d') <= $now)
                    $totalSlpWeek += $slp-> daily;
            }
 
        }

        $statusMenu = "dashboard";
        $idPlayer = 0;
        return view('admin.dashboard',compact("totalSlpToday", "totalSlpYesterday", "totalSlpWeek", "statusMenu" , "idPlayer"));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();

        for ($i = 0; $i < 7; $i++) {
            $totalSlp = 0;
            $playersAll = Player::with(['totalSLP' => function($q) use($years, $month, $i) {
                $q->where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%"); 
            }])->get();

            foreach($playersAll as $player){
                foreach($player->totalSLP as $slp){
                    $totalSlp += $slp->daily;
                }
            }

            $listDay[$i]['day'] = Carbon::now()->subDay(6-$i)->format('d');
            $listDay[$i]['totalSlp'] = $totalSlp;
        }

        $listDayJson = json_encode($listDay);
        echo json_encode($listDay);
    }

    public function listPlayers(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.dashboard'));
        }

        $playersAll= Player::orderBy("name","asc")->get();
        $playerSelect = array();

        $statusMenu = "players";
        return view('admin.listPlayers', compact('statusMenu', 'playersAll', 'playerSelect'));
    }

    public function formPlayer(Request $request)
    {

        $status = false ;
        $errorMsg;
        if(empty($request->playerSelect))
        {
            if(Player::where('user',  $request->user)->first())
            {
                $status = true;
                $errorMsg = "El usuario ya e encuentra registrado en el sistema";
            }else if(Player::where('wallet', str_replace("ronin:","", $request->wallet))->first())
            {
                $status = true;
                $errorMsg = "Billetera ingresado ya se encuentra registrado en el sistema";
            }
            else if(Player::where('email', $request->email)->first())
            {
                $status = true;
                $errorMsg = "Correo Electrónico del jugador ya se encuentra registrado en el sistema";
            }
            else if(Player::where('emailGame', $request->emailGame)->first())
            {
                $status = true;
                $errorMsg = "Correo Electrónico del Axie infinity ya se encuentra registrado en el sistema!";
            }
        }else
        {
            if(Player::where('wallet', "!=", str_replace("ronin:", "", $request->wallet))->where('user',  $request->user)->first())
            {
                $status = true;
                $errorMsg = "El usuario ya e encuentra registrado en el sistema";
            }else if(Player::where('wallet', "!=", str_replace("ronin:", "", $request->wallet))->where('email', $request->email)->first())
            {
                $status = true;
                $errorMsg = "Correo Electrónico del jugador ya se encuentra registrado en el sistema";
            }
            else if(Player::where('wallet', "!=", str_replace("ronin:", "", $request->wallet))->where('emailGame', $request->emailGame)->first())
            {
                $status = true;
                $errorMsg = "Correo Electrónico del Axie infinity ya se encuentra registrado en el sistema!";
            }
        }
        

        if($status && empty($request->statusApi)){
            Session::flash('message', $errorMsg);
            return Redirect::back();
        }else if($status && $request->statusApi == 'true'){
            return response()->json(['statusCode' => 400, 'message' => $errorMsg]);
        }

        if(empty($request->statusApi))
            $file = $request->file('codeQr');
        else
            $file = base64_decode($request->image);

        if(empty($request->playerSelect))
        {
            $player =  Player::create(
                [
                    'name'          => $request->name,
                    'phone'         => $request->digPhone."-".$request->phone,
                    'telegram'      => $request->telegram,
                    'email'         => $request->email,
                    'reference'     => $request->reference,
                    'user'          => $request->user,
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
            $player->user          = $request->user;
            $player->emailGame     = $request->emailGame;
            $player->passwordGame  = bcrypt($request->passwordGame);
            $player->save();
        }
        
        if(!empty($file) && !empty($request->urlPrevius)){
            \Storage::disk('public')->delete($request->urlPrevius);
        }
        
        if(!empty($file) && empty($request->statusApi)){

            $urlCodeQr = 'players/'.$player->id.'/codeQr-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
            \Storage::disk('public')->put($urlCodeQr, file_get_contents($request->file('codeQr'))); 
            
            $player->urlCodeQr = $urlCodeQr;
            $player->save();

        }else if(!empty($file) && !empty($request->statusApi)) {

            $urlCodeQr = 'players/'.$player->id.'/codeQr-'.Carbon::now()->format('d-m-Y_H-i-s').'.jpg';
            \Storage::disk('public')->put($urlCodeQr, $file); 

            $player->urlCodeQr = $urlCodeQr;
            $player->save();
        }

        (new Player)->forceFill([
            'email' => $request->email,
        ])->notify(
            new NewPlayer($player, $request->passwordGame)
        );  

        $this->getUpdateAnimal($player->id, $player->wallet);

        if($request->statusApi == 'true')
            return response()->json(['statusCode' => 201, 'message' => "saved correctly! "]);
        else
            return redirect()->route('admin.listPlayers');
    }

    public function getUpdateAnimal($id, $wallet){
        $urlApi = [
            'https://api.axie.com.ph/get-axies/0x', 'https://axie-proxy.secret-shop.buzz/_axiesPlease/0x'
        ];

        foreach ($urlApi as $key => $api){
            $count = 0;

            $url = $api.$wallet;
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            $resultApi = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($key == 0 && $resultApi && $resultApi['results']){

                foreach($resultApi['available_axies']['results'] as $axie)
                {
                    $count ++;
                    $name = explode(" ", $axie['name']);

                    Animal::updateOrCreate(
                        [
                            'player_id'    => $id,
                            'code'          => $axie['id'],
                        ],
                        [
                            'name'          => $name[0],
                            'nomenclature'  => $name[1],
                            'type'          => $axie['class'],
                            'image'         => $axie['image'],
                        ]
                    );

                }

            }else if($key == 1 && $resultApi && isset($resultApi['available_axies']['results'])){

                foreach($resultApi['available_axies']['results'] as $axie)
                {
                    $count ++;
                    $name = explode(" ", $axie['name']);
                    Animal::updateOrCreate(
                        [
                            'player_id'    => $id,
                            'code'          => $axie['id'],
                        ],
                        [
                            'name'          => $name[0],
                            'nomenclature'  => $name[1],
                            'type'          => $axie['class'],
                            'image'         => $axie['image'],
                        ]
                    );
                }
            }

        }

    }

    public function showPlayer(Request $request)
    {
        $player = Player::whereId($request->id)->with('animals')->first();

        $returnHTML=view('admin.modal.detailsPlayer', compact('player'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function editPlayer(Request $request)
    {
        $playerSelect = Player::whereId($request->id)->first();

        $returnHTML=view('admin.modal.player', compact('playerSelect'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function listDaily(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.dashboard'));
        }

        $orderBy = "ASC";
        $startDate = Carbon::now()->setDay(1)->subMonth(1)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        if($request->all()){
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $orderBy = $request->orderBy;
        }

        $playersAll = Player::with(['totalSLP' => function($q) use($startDate, $endDate) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$endDate);
        }])->get();


        $statusMenu = "gameHistory";
        return view('admin.listDaily', compact('statusMenu', 'startDate', 'endDate', 'playersAll', 'orderBy'));
    }


    public function newSLP(Request $request)
    {
        $players = Player::all();

        $returnHTML=view('admin.modal.newSlp', compact('players'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function verifySLP(Request $request)
    {
        // if date is used
        //$date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        // if date with carbon is used
        $date = $request->date;

        $player = Player::whereId($request->id)->with(['totalSLP' => function($q) use($date) {
            $q->whereDate('date', $date);
        }])->first();

        if(count($player->totalSLP) == 0 && $request->total >0)
            return response()->json(['statusCode' => 201, 'statusTotal' => true]);
        else if(count($player->totalSLP) == 0 && $request->total <0)
            return response()->json(['statusCode' => 201, 'statusTotal' => false]);
        else
            return response()->json(['statusCode' => 401,]);
    }

    public function formSLP(Request $request)
    {
        // if date is used
        //$date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        // if date with carbon is used
        $date = $request->date;

        $player = Player::whereId($request->playerId)->with('lastSLP')->first();
        $totaldaily = $player->lastSLP->total + intval($request->total);

        TotalSlp::updateOrCreate(
            [
                'player_id'     => $request->playerId,
                'date'          => $date,
                'total'     => $totaldaily,
                'daily'     => intval($request->total),
            ]
        );

        return redirect()->route('admin.listDaily');
    }

    public function apiSLP(){
        $listTotal = TotalSLP::all();

        foreach($listTotal as $item){
            if($item->daily <= 75)
                $item->totalPlayer = $item->daily - ($item->daily * 0.15);
            else 
                $item->totalPlayer = $item->daily - ($item->daily * 0.2);

            $item->save();
        }

        dd("completado");
    }

}
