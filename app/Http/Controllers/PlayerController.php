<?php

namespace App\Http\Controllers;

use Session;
use App\Animal;
use App\Player;
use App\TotalSlp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PlayerController extends Controller
{
    public function login(Request $request)
    {

        $user = Player::where("wallet", str_replace("ronin:", "", $request->wallet))->first();
        if($user){
            Auth::guard('web')->logout();
            $request->session()->flush();
        }else{
            Auth::loginUsingId($user->id);
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

        $totalSlpToday = 0;
        $totalSlpYesterday= 0;
        $totalSlpWeek = 0;
        $startDate = Carbon::now()->setDay(1)->subMonth(2)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');
        $yesteday = Carbon::now()->subDays(1)->format('Y-m-d');
        $startWeek = Carbon::now()->subDays(6)->format('Y-m-d');

        $player = Player::whereId(Auth::guard('web')->id())->with(['totalSLP' => function($q) use($startDate, $now) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$now); 
        }])->first();

        foreach($player->totalSLP as $slp){

            if($now == Carbon::parse($slp->date)->format('Y-m-d'))
                $totalSlpToday += $slp->daily;

            if($yesteday == Carbon::parse($slp->date)->format('Y-m-d'))
                $totalSlpYesterday += $slp-> daily;

            if(Carbon::parse($slp->date)->format('Y-m-d') >= $startWeek && Carbon::parse($slp->date)->format('Y-m-d') <= $now)
                $totalSlpWeek += $slp-> daily;
        }

        $statusMenu = "dashboard";
        $idPlayer = 0;
        return view('player.dashboard',compact("totalSlpToday", "totalSlpYesterday", "totalSlpWeek", "statusMenu" , "idPlayer"));
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
            }])->get();

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

    public function listDaily(Request $request)
    {  
        if (!Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect(route('player.login'));
        }elseif (!Auth::guard('web')->check() && Auth::guard('admin')->check()){
            return redirect(route('admin.dashboard'));
        }

        $orderBy = "ASC";
        $startDate = Carbon::now()->setDay(1)->subMonth(1)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        if($request->all()){
            $startDate=Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
            $endDate=Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');
            $orderBy = $request->orderBy;
        }

        $player = Player::whereId(Auth::guard('web')->id())->with(['totalSLP' => function($q) use($startDate, $endDate, $orderBy) {
            $q->whereDate('date', ">=",$startDate)
                ->whereDate('date', "<=",$endDate)
                ->orderBy('date', $orderBy);
        }])->first();


        $statusMenu = "gameHistory";
        return view('player.listDaily', compact('statusMenu', 'startDate', 'endDate', 'player', 'orderBy'));
    }
}
