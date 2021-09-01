<?php

namespace App\Http\Controllers;

use Session;
use App\Animal;
use App\Player;
use App\Rate;
use App\TotalSlp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PlayerController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(Auth::guard('web')->attempt(['emailGame' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('player.dashboard'));
        } 

        Session::flash('message', "El correo o la contraseña es incorrecta!");
        return Redirect::back();
    }

    public function dashboard(Request $request)
    {
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $now = Carbon::now()->format('Y-m-d');
        $player = Player::whereId(Auth::guard('web')->id())->with('group')->with(['totalSLP' => function($q) use($now) {
            $q->where('date', "!=", $now)->orderBy('date','DESC'); 
        }])->first();

        app('App\Http\Controllers\Controller')->updateSlp($player);

        $priceSlp = app('App\Http\Controllers\Controller')->getPriceSlp();

        $totalSlpToday = 0;
        $totalSlpYesterday = 0;
        $totalSlpUnclaimed = 0;
        $now = Carbon::now()->format('Y-m-d');
        $yesteday = Carbon::yesterday()->format('Y-m-d');

        $player = Player::whereId(Auth::guard('web')->id())->with("totalSLP")->first();
        $dateClaim = Carbon::parse($player->dateClaim)->format('Y-m-d');
        foreach($player->totalSLP as $slp){

            if($now == Carbon::parse($slp->date)->format('Y-m-d'))
                $totalSlpToday += $slp->daily;

            if($yesteday == Carbon::parse($slp->date)->format('Y-m-d'))
                $totalSlpYesterday += $slp-> daily;

            if(Carbon::parse($slp->date)->format('Y-m-d') >= $dateClaim && Carbon::parse($slp->date)->format('Y-m-d') <= $now)
                $totalSlpUnclaimed += $slp-> daily;
                
        }

        $statusMenu = "dashboard";
        $idPlayer = 0;
        return view('player.dashboard',compact("totalSlpToday", "totalSlpYesterday", "totalSlpUnclaimed", "statusMenu" , "idPlayer", "priceSlp"));
    }

    public function dataGraphic(Request $request)
    {
        $month = Carbon::now()->format('m');
        $years = Carbon::now()->format('Y');
        $listDay= array();

        for ($i = 0; $i < 7; $i++) {
            $totalSlp = 0;
            $player = Player::whereId(Auth::guard('web')->id())->with(['totalSLP' => function($q) use($years, $month, $i) {
                $q->where("date", 'like', "%".Carbon::now()->format($years.'-'.$month.'-'.Carbon::now()->subDay(6-$i)->format('d'))."%"); 
            }])->first();

            foreach($player->totalSLP as $slp){
                $totalSlp += $slp->daily;
            }

            $listDay[$i]['day'] = Carbon::now()->subDay(6-$i)->format('d');
            $listDay[$i]['totalSlp'] = $totalSlp;
        }

        $listDayJson = json_encode($listDay);
        echo json_encode($listDay);
    }

    public function profile(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $player= Player::whereId(Auth::guard('web')->id())->with('animals')->first();

        $statusMenu = "profile";
        return view('player.profile', compact('statusMenu', 'player'));
    }

    public function rate(){
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $player = Player::whereId(Auth::guard('web')->id())->first();
        $rate = Rate::where('admin_id', $player->admin_id)->first();

        if(!$rate)
            $rate = Rate::where('admin_id', 1)->first();

        $statusMenu = "rate";
        return view('player.rate', compact('statusMenu', 'rate'));

    }

    public function listDaily(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $startDate = Carbon::now()->setDay(1)->format('Y-m-d');
        $endDate = Carbon::now()->setDay(15)->format('Y-m-d');
        $statusBiweekly = true;
        $initialDay = 1;
        $finalDay = 15;
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $monthDate = Carbon::now()->format('n');
        $yearDate = Carbon::now()->format('Y');

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

            if($statusBiweekly){
                $startDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->format('Y-m-d');
                $endDate = Carbon::parse($yearDate.'-'.$monthDate.'-15')->format('Y-m-d');
            }else{
                $startDate = Carbon::parse($yearDate.'-'.$monthDate.'-16')->format('Y-m-d');
                $endDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->endOfMonth()->format('Y-m-d');
            }

        }

        $player = Player::whereId(Auth::guard('web')->id())->with(['totalSLP' => function($q) use($startDate, $endDate) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$endDate);
        }])->first();


        $statusMenu = "gameHistory";
        return view('player.listDaily', compact('statusMenu','statusBiweekly', 'startDate', 'endDate', 'player', 'statusBiweekly', 'initialDay', 'finalDay', 'months', 'monthDate', 'yearDate'));
    }

    public function listClaim (Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $initialDay = 1;
        $finalDay = 15;
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $monthDate = Carbon::now()->format('n');
        $yearDate = Carbon::now()->format('Y');


        if($request->all()){
            $statusBiweekly = filter_var($request->statusBiweekly, FILTER_VALIDATE_BOOLEAN);
            $monthDate = $request->monthDate;
            $yearDate = $request->yearDate;
        }

        $startDate = Carbon::parse($yearDate.'-'.$monthDate.'-15')->format('Y-m-d');
        $endDate = Carbon::parse($yearDate.'-'.$monthDate.'-1')->endOfMonth()->format('Y-m-d');

        $player = Player::whereId(Auth::guard('web')->id())->with(['claims' => function($q) use($startDate, $endDate) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$endDate);
        }])->first();
        
        $listDate = [$startDate, $endDate];

        $statusMenu = "claimHistory";
        return view('player.listClaim', compact('statusMenu', 'listDate', 'player', 'initialDay', 'finalDay', 'months', 'monthDate', 'yearDate'));
    }
}
