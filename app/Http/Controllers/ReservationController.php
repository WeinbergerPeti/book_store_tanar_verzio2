<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(){
        $reservations =  Reservation::all();
        return $reservations;
    }
    
    public function show($id)
    {
        $reservations = Reservation::find($id);
        return $reservations;
    }
    public function destroy($id)
    {
        Reservation::find($id)->delete();
    }
    public function store(Request $request)
    {
        $reservation = new Reservation();
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->message = $request->message;
        $reservation->save(); 
    }

    public function update(Request $request, $id)
    {
        //a book_id ne változzon! mert akkor már másik példányról van szó
        $reservation = Reservation::find($id);
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->message = $request->message;
        $reservation->save();        
    }

    // Hány darab előjegyzése van a bejelentkezett felhasználónak?
    public function elojegyzes()
    {
        $user=Auth::user();
        $reservations = DB::table("reservations as r")
        ->join("users as u", "r.user_id", "=", "u.id")
        ->where("u.id", "=", $user)
        ->count();
        return $reservations;
    }
}
