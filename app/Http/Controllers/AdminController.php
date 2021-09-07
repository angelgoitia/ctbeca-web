<?php

namespace App\Http\Controllers;

use Session;
use App\Animal;
use App\Claim;
use App\Player;
use App\Rate;
use App\TotalSlp;
use App\User;
use Carbon\Carbon;
use App\Notifications\InfoGroup;
use App\Notifications\NewGroup;
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

        $now = Carbon::now()->format('Y-m-d');
        /* Update SLP Daily */
        /* if(Auth::guard('admin')->id() == 1)
            $players = Player::with('group')->with(['totalSLP' => function($q) use($now) {
                $q->where('date', "!=", $now)->orderBy('date','DESC'); 
            }])->get();
        else
            $players = Player::where('admin_id', Auth::guard('admin')->id())->with('group')->with(['totalSLP' => function($q) use($now) {
                $q->where('date', "!=", $now)->orderBy('date','DESC'); 
            }])->get();

        app('App\Http\Controllers\Controller')->updateSlpPlayers($players); */

        $priceSlp = app('App\Http\Controllers\Controller')->getPriceSlp();

        $totalSlpToday = 0;
        $totalSlpYesterday= 0;
        $totalSlpUnclaimed = 0;
        $totalSlpPlayer = 0;
        $totalSlpManager = 0;
        $totalSlpAll = 0;
        $yesteday = Carbon::yesterday()->format('Y-m-d');
        
        if(Auth::guard('admin')->id() == 1)
            $playersAll = Player::with("totalSLP")->get();
        else
            $playersAll = Player::where('admin_id', Auth::guard('admin')->id())->with("totalSLP")->get();

        foreach($playersAll as $player){
            $dateClaim = Carbon::parse($player->dateClaim)->format('Y-m-d');
            foreach($player->totalSLP as $slp){

                if($now == Carbon::parse($slp->date)->format('Y-m-d'))
                    $totalSlpToday += $slp->daily;

                if($yesteday == Carbon::parse($slp->date)->format('Y-m-d'))
                    $totalSlpYesterday += $slp-> daily;

                if(Carbon::parse($slp->date)->format('Y-m-d') > $dateClaim && Carbon::parse($slp->date)->format('Y-m-d') <= $now)
                    $totalSlpUnclaimed += $slp-> daily;

                $totalSlpAll += $slp-> daily;
                $totalSlpPlayer += ($slp-> daily - $slp->totalManager);
                $totalSlpManager += $slp->totalManager;
            }
 
        }

        $statusMenu = "dashboard";
        $idPlayer = 0;
        return view('admin.dashboard',compact("totalSlpToday", "totalSlpYesterday", "totalSlpUnclaimed", "totalSlpManager", "totalSlpPlayer", "totalSlpAll", "statusMenu" , "idPlayer", 'priceSlp'));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();

        for ($i = 0; $i < 17; $i++) {
            $totalSlp = 0;

            if(Auth::guard('admin')->id() == 1)
                $playersAll = Player::with(['totalSLP' => function($q) use($years, $month, $i) {
                    $q->where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(16-$i)->format('d'))."%"); 
                }])->get();
            else
                $playersAll = Player::where('admin_id', Auth::guard('admin')->id())->with(['totalSLP' => function($q) use($years, $month, $i) {
                    $q->where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(16-$i)->format('d'))."%"); 
                }])->get();

            foreach($playersAll as $player){
                foreach($player->totalSLP as $slp){
                    $totalSlp += $slp->daily;
                }
            }

            $listDay[$i]['day'] = Carbon::now()->subDay(16-$i)->format('d');
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

        if(Auth::guard('admin')->id() == 1)
            $playersAll= Player::orderBy("name","asc")->get();
        else
            $playersAll= Player::where('admin_id', Auth::guard('admin')->id())->orderBy("name","asc")->get();

        $statusMenu = "players";
        return view('admin.listPlayers', compact('statusMenu', 'playersAll'));
    }

    public function formPlayer(Request $request)
    {
        $status = false ;
        $errorMsg;

        $rate = Rate::whereId(1)->first();

        if(!$rate){
            $status = true;
            $errorMsg = "Deben crear una tasa";
        }

        if(empty($request->playerSelect))
        {
            if(Player::where('wallet', str_replace("ronin:","", $request->wallet))->first())
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
            if(Player::where('wallet', "!=", str_replace("ronin:", "", $request->wallet))->where('email', $request->email)->first())
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

        if(empty($request->statusApi)){
            if($request->reference == "Otro")
                $reference = $request->referenceOther;
            else
                $reference = $request->reference;

            $file = $request->file('codeQr');
        }else{
            $reference = $request->reference;
            $file = base64_decode($request->image);
        }

        if(Auth::guard('admin')->check()){
            if(Auth::guard('admin')->id() == 1)
                $admin_id = intval($request->group);
            else
                $admin_id = Auth::guard('admin')->id();
        }else{
            $admin_id = intval($request->group);
        }

        if(empty($request->playerSelect))
        {
            $player =  Player::create(
                [
                    'name'          => $request->name,
                    'phone'         => $request->digPhone."-".$request->phone,
                    'telegram'      => $request->telegram,
                    'email'         => $request->email,
                    'reference'     => $reference,
                    'emailGame'     => $request->emailGame,
                    'passwordGame'  => bcrypt($request->passwordGame),
                    'wallet'        => str_replace("ronin:","", $request->wallet),
                    'admin_id'      => $admin_id,
                    'dateClaim'     => Carbon::createFromFormat('d/m/Y', $request->dateClaim)->format('Y-m-d'),
                ]
            );
            
            $player->admin_id      = $admin_id;
            $player->save();
            
        }else{
            $player =  Player::where('wallet', str_replace("ronin:","", $request->wallet))->with('animals')->first();
            $player->name          = $request->name;
            $player->phone         = $request->digPhone."-".$request->phone;
            $player->telegram      = $request->telegram;
            $player->email         = $request->email;
            $player->reference     = $reference;
            $player->emailGame     = $request->emailGame;
            $player->passwordGame  = bcrypt($request->passwordGame);
            $player->admin_id      = $admin_id;
            $player->dateClaim     = Carbon::createFromFormat('d/m/Y', $request->dateClaim)->format('Y-m-d');
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

        $this->getUpdateAnimal($player);

        if($request->statusApi == 'true')
            return response()->json(['statusCode' => 201, 'message' => "saved correctly! "]);
        else
            return redirect()->route('admin.listPlayers');
    }

    public function getUpdateAnimal($player){

        $urlApi = [
            'https://api.axie.com.ph/get-axies/0x', 'https://axie-proxy.secret-shop.buzz/_axies/0x'
        ];

       foreach ($urlApi as $key => $api){
           $results = [];
           $total = 0;
           $count = 1;
           
           $url = $api.$player->wallet;
           $ch = curl_init($url);
           
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

           $resultApi = json_decode(curl_exec($ch), true);
           curl_close($ch);

           if($key == 0 && $resultApi && isset($resultApi['results']) && count($resultApi['results']) > 0 ){
               $results = $resultApi['results'];
               $total = count($resultApi['results']);
           }
           else if($key == 1 && $resultApi && isset($resultApi['available_axies']['results']) && $resultApi['available_axies']['total'] > 0 ){
               $results = $resultApi['available_axies']['results'];
               $total =  $resultApi['available_axies']['total'];
           }

           if(isset($resultApi['results']) || isset($resultApi['available_axies']['results'])){
                foreach($player->animals as $key => $animal){

                    if($total < count($player->animals) && $count > $total)
                        $animal->delete();
                    else{
                        $name = explode(" ", $results[$key]['name']);
                        $animal->code = $results[$key]['id']; 
                        $animal->name = $name[0]; 
                        $animal->nomenclature = $name[1]; 
                        $animal->type = $results[$key]['class'];
                        $animal->image = $results[$key]['image']; 
                        $animal->save();
                    }
    
                    $count++;
                }
    
                if($count <= $total){
                    for ($i = $count-1; $i < $total; $i++){
                        $name = explode(" ", $results[$i]['name']);
                        Animal::create([
                            'player_id'     => $player->id,
                            'code'          => $results[$i]['id'],
                            'name'          => $name[0],
                            'nomenclature'  => $name[1],
                            'type'          => $results[$i]['class'],
                            'image'         => $results[$i]['image'],
                        ]);
                    }
                }
    
    
                if($total > 0)
                    break;
           }

       }

    }

    public function showPlayer(Request $request)
    {
        $player = Player::whereId($request->id)->with('animals')->with('group')->first();
        $returnHTML=view('admin.modal.detailsPlayer', compact('player', 'groups'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function editPlayer(Request $request)
    {
        $playerSelect = Player::whereId($request->id)->with('group')->first();
        $players = Player::all();
        $groups = User::where("id", '!=', 1)->get();

        $rate = Rate::whereId(1)->first();

        if(!$rate)
            return response()->json(array('statusCode' => 400, 'message'=>'Deben crear una tasa'));

        if(count($groups) == 0)
            return response()->json(array('statusCode' => 400, 'message'=>'Deben crear un nuevo grupo'));


        $returnHTML=view('admin.modal.player', compact('playerSelect', 'players', 'groups'))->render();
        return response()->json(array('statusCode' => 201, 'html'=>$returnHTML));
    }

    public function listDaily(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.dashboard'));
        }

        $startDate = Carbon::now()->setDay(1)->format('Y-m-d');
        $endDate = Carbon::now()->setDay(15)->format('Y-m-d');
        $statusBiweekly = true;
        $initialDay = 1;
        $finalDay = 15;
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $monthDate = Carbon::now()->format('n');
        $yearDate = Carbon::now()->format('Y');
        $groupId = 0; 
        $groups = User::where('id', '!=', 1)->get();

        if(Carbon::now()->format('d') > 15){
            $startDate = Carbon::now()->setDay(15)->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
            $statusBiweekly = false;
            $initialDay = 16;
            $finalDay = Carbon::now()->endOfMonth()->format('d');
        }

        if($request->all()){
            $statusBiweekly = filter_var($request->statusBiweekly, FILTER_VALIDATE_BOOLEAN);
            $monthDate = $request->monthDate+1;
            $yearDate = $request->yearDate;

            $groupId = $request->groupId;

            if($statusBiweekly){
                $startDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->format('Y-m-d');
                $endDate = Carbon::parse($yearDate.'-'.$monthDate.'-15')->format('Y-m-d');
            }else{
                $startDate = Carbon::parse($yearDate.'-'.$monthDate.'-16')->format('Y-m-d');
                $endDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->endOfMonth()->format('Y-m-d');
            }

        }

        if(Auth::guard('admin')->id() != 1)
            $groupId = Auth::guard('admin')->id();


        if(Auth::guard('admin')->id() == 1 && $groupId == 0)
            $playersAll = Player::with(['totalSLP' => function($q) use($startDate, $endDate) {
                $q->whereDate('date', ">=",$startDate)
                    ->whereDate('date', "<=",$endDate);
            }])->get();
        else
            $playersAll = Player::where('admin_id', $groupId)->with(['totalSLP' => function($q) use($startDate, $endDate) {
                $q->whereDate('date', ">=",$startDate)
                    ->whereDate('date', "<=",$endDate);
            }])->get();
        


        $statusMenu = "gameHistory";
        return view('admin.listDaily', compact('statusMenu', 'startDate', 'endDate', 'playersAll', 'statusBiweekly', 'initialDay', 'finalDay', 'months', 'monthDate', 'yearDate', 'groups', 'groupId'));
    }


    public function newSLP(Request $request)
    {
        $startDate = Carbon::now()->setDay(1)->format('Y-m-d');

        if(Carbon::now()->format('d') > 15)
            $startDate = Carbon::now()->setDay(15)->format('Y-m-d');

        $idSlp = $request->idSlp;
        $selectPlayer = array();
        $status = true;

        if($request->idPlayer > 0){
            $selectPlayer = Player::whereId($request->idPlayer)->with(['totalSLP' => function($q) use($idSlp) {
                $q->whereId($idSlp);
            }])->first();
            $status = false;
        }

        if(Auth::guard('admin')->id() == 1)
            $players = Player::all();
        else
            $players = Player::where('admin_id', Auth::guard('admin')->id())->get(); 

        $returnHTML=view('admin.modal.newSlp', compact('players', 'selectPlayer'))->render();
        return response()->json(array('html'=>$returnHTML, 'startDate' => $startDate, 'statusDate' => $status));
    }

    public function verifySLP(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        $player = Player::whereId($request->id)->with(['totalSLP' => function($q) use($date) {
            $q->whereDate('date', $date);
        }])->first();

        if(count($player->totalSLP) == 0 && $request->totalDaily >= 0)
            return response()->json(['statusCode' => 201, 'statusTotal' => true]);
        else if(count($player->totalSLP) == 0 && $request->totalDaily <0)
            return response()->json(['statusCode' => 201, 'statusTotal' => false]);
        else
            return response()->json(['statusCode' => 401,]);
    }

    public function formSLP(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $selectPlayer = json_decode($request->selectPlayer);

        if($selectPlayer)
            $playerId = $selectPlayer->id;
        else
            $playerId = $request->playerId;

        $player = Player::whereId($playerId)->with(['totalSLP' => function($q) use($date) {
            $q->whereDate('date', '<', $date);
        }])->first();

        $totaldaily = intval($request->totalDaily);
        $total = count($player->totalSLP) > 0? $player->totalSLP[0]->total + $totaldaily : $totaldaily;
        
        $rate = null;

        if($player->group)
            $rate  = Rate::where('admin_id', $player->group->id)->first();

        if(!$rate)
            $rate  = Rate::where('admin_id', 1)->first();


        if($selectPlayer)
            TotalSlp::whereId($selectPlayer->total_s_l_p[0]->id)->update(
                [
                    'date'          => $date,
                    'total'         => $total,
                    'daily'         => $totaldaily,
                    'totalManager'  => $totaldaily <= $rate->lessSlp ? ($totaldaily - ($totaldaily * $rate->lessPercentage) / 100) : ($totaldaily - ($totaldaily * $rate->greaterPercentage) / 100), 
                ]
            );
        else
            TotalSlp::create(
                [
                    'player_id'     => $playerId,
                    'date'          => $date,
                    'total'         => $total,
                    'daily'         => $totaldaily,
                    'totalManager'  => $totaldaily <= $rate->lessSlp ? ($totaldaily - ($totaldaily * $rate->lessPercentage) / 100) : ($totaldaily - ($totaldaily * $rate->greaterPercentage) / 100), 
                ]
            );

        app('App\Http\Controllers\Controller')->updateSlpManual($player->id, $player->dateClaim);

        if(Carbon::now()->format('d') == 15 || Carbon::now()->format('d') == Carbon::now()->endOfMonth()->format('d')){
            
            if(Carbon::createFromFormat('d/m/Y', $request->date)->format('d') <= 15 || (Carbon::createFromFormat('d/m/Y', $request->date)->format('d') > 15 && Carbon::createFromFormat('d/m/Y', $request->date)->endOfMonth()->format('d') > 28 ))
                $day = 15;
            else if (Carbon::createFromFormat('d/m/Y', $request->date)->format('d') > 15 && Carbon::createFromFormat('d/m/Y', $request->date)->endOfMonth()->format('d') == 28)
                $day = 13;

            $date = Carbon::parse($player->dateClaim);
            $now = Carbon::now();

            $diff = $date->diffInDays($now);
            if($diff >= $day && $player->tokenFCM){
                app('App\Http\Controllers\Controller')->claimPlayer($player->id, $total);
            }
        }

        return redirect()->route('admin.listDaily');
    }

    public function rates(){
        $rate = Rate::where('admin_id',1)->first();
        $rates = Rate::where('admin_id', '!=', 1)->with("admin")->get();
        $statusMenu = "rates";
        return view('admin.listRates', compact('statusMenu', 'rate', 'rates'));
    }

    public function rate(){
        $admin = Auth::guard('admin')->id();
        $rate = Rate::where('admin_id', $admin)->with("admin")->first();

        if(!$rate)
            $rate = Rate::where('admin_id', 1)->with("admin")->first();

        $statusMenu = "rate";
        return view('admin.rate', compact('statusMenu', 'rate'));
    }

    public function formRate(Request $request){
        Rate::updateOrCreate(
            [
                'admin_id'          => Auth::guard('admin')->id(),
            ],
            [
                'lessSlp'           => $request->lessSlp,
                'lessPercentage'    => $request->lessPercentage,
                'greaterSlp'        => $request->greaterSlp,
                'greaterPercentage' => $request->greaterPercentage,
            ]
        );

        if(Auth::guard('admin')->id() == 1)
            return redirect()->route('admin.rates');

        return redirect()->route('admin.rate');
    }

    public function listClaim (Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('admin.login'));
        }elseif (Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.dashboard'));
        }

        $selectDate = Carbon::now()->setDay(15)->format('Y-m-d');
        $statusBiweekly = true;
        $initialDay = 1;
        $finalDay = 15;
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $monthDate = Carbon::now()->format('n');
        $yearDate = Carbon::now()->format('Y');
        $groups = User::where('id', '!=', 1)->get();
        $groupId = 0;


        if(Carbon::now()->format('d') < 15){
            $statusBiweekly = false;
            $monthDate = Carbon::now()->subMonth()->format('n');
            $selectDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        }



        if($request->all()){
            $statusBiweekly = filter_var($request->statusBiweekly, FILTER_VALIDATE_BOOLEAN);
            $monthDate = $request->monthDate;
            $yearDate = $request->yearDate;
            $groupId =$request->groupId;

            if($statusBiweekly)
                $selectDate = Carbon::parse($yearDate.'-'.$monthDate.'-15')->format('Y-m-d');
            else
                $selectDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->endOfMonth()->format('Y-m-d');

        }

        if(Auth::guard('admin')->id() != 1)
            $groupId = Auth::guard('admin')->id();

        if(Auth::guard('admin')->id() == 1 && $groupId == 0)
            $playersAll = Player::with(['claims' => function($q) use($selectDate) {
                $q->whereDate('date', $selectDate);
            }])->get();
        else
            $playersAll = Player::where('admin_id', $groupId)->with(['claims' => function($q) use($selectDate) {
                $q->whereDate('date', $selectDate);
            }])->get();


        $statusMenu = "claimHistory";
        return view('admin.listClaim', compact('statusMenu', 'selectDate', 'playersAll', 'statusBiweekly', 'initialDay', 'finalDay', 'months', 'monthDate', 'yearDate', 'groups', 'groupId'));
    }

    public function listGroup(){
        $groups = User::where("id", '!=', 1)->get();

        $statusMenu = "groups";
        return view('admin.listGroups', compact('statusMenu', 'groups'));
    }

    public function editGroup(Request $request){
        $groupSelect = User::whereId($request->id)->first();

        $groups = User::where("id", '!=', 1)->get();

        $returnHTML=view('admin.modal.group', compact('groupSelect', 'groups'))->render();
        return response()->json(array('html'=>$returnHTML));
    }

    public function verifyGroup(Request $request){
        $listError = array();

        $group = User::where('email', $request->email)->where('id', '!=', $request->id)->first();
        if($group) 
            array_push($listError, "Correo Electrónico ingresado ya existe");

        $group = User::where('nameGroup', $request->nameGroup)->where('id', '!=', $request->id)->first();
        if($group) 
            array_push($listError, "Nombre del Grupo ingresado ya existe");

        return response()->json(['statusCode' => 201, 'listError' => $listError, 'listErrorLength' => count($listError)]);
    }

    public function formGroup(Request $request)
    {
        if(empty($request->groupSelect))
        {
            $group =  User::create(
                [
                    'name'          => $request->name,
                    'nameGroup'     => $request->nameGroup,
                    'email'         => $request->email,
                    'password'  => bcrypt($request->password),
                ]
            );

        }else{
            $group =  User::where('email', $request->email)->first();
            $group->name      = $request->name;
            $group->nameGroup = $request->nameGroup;
            $group->password  = bcrypt($request->password);
            $group->save();
        }

        (new User)->forceFill([
            'email' => $request->email,
        ])->notify(
            new NewGroup($group, $request->password)
        );  

        return redirect()->route('admin.listGroup');
    }

    public function apiSLP(){}
}
