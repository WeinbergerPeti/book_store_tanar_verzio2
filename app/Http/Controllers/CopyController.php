<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Copy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CopyController extends Controller
{
    //
    public function index(){
        $copies =  Copy::all();
        return $copies;
    }

    public function index2(){
        $copies =  Copy::all();
        return $copies;
    }
    
    public function show($id)
    {
        $copies = Copy::find($id);
        return $copies;
    }
    public function destroy($id)
    {
        Copy::find($id)->delete();
    }
    public function store(Request $request)
    {
        $copy = new Copy();
        $copy->book_id = $request->book_id;
        $copy->hardcovered = $request->hardcovered;
        $copy->publication = $request->publication;
        $copy->status = 0;
        $copy->save(); 
    }

    public function update(Request $request, $id)
    {
        //a book_id ne változzon! mert akkor már másik példányról van szó
        $copy = Copy::find($id);
        $copy->hardcovered = $request->hardcovered;
        $copy->publication = $request->publication;
        $copy->status = $request->status;
        $copy->save();        
    }

    public function copies_pieces($title)
    {	
        $copies = Book::with('copy_c')->where('title','=', $title)->count();
        return $copies;
    }

   

    //view-k:

    public function newView()
    {
        //új rekord(ok) rögzítése
        $books = Book::all();
        return view('copy.new', ['books' => $books]);
    }

    public function editView($id)
    {
        $books = Book::all();
        $copy = Copy::find($id);
        return view('copy.edit', ['books' => $books, 'copy' => $copy]);
    }

    public function listView()
    {
        $copies = Copy::all();
        //copy mappában list blade
        return view('copy.list', ['copies' => $copies]);
    }

    // 1. feladat
    public function bookCopyCount($cim)
    {
        $copies = DB::table("copies as c")
        ->join("books as b", "c.book_id", "=", "b.book_id")
        ->where("b.title", "=", $cim)
        ->count();
        return $copies;
    }

    // 2. feladat
    // Add meg a keménykötésű példányokat szerzővel és címmel! (ha megy, akkor a bármilyet tudj megadni paraméterrel; kemény: 1, puha: 0, hardcovered a mező)
    public function hardCover($hardcovered)
    {
        $copies = DB::table("copies as c")
        ->select("b.author", "b.title")
        ->join("books as b", "c.book_id", "=", "b.book_id")
        ->where("c.hardcovered", "=", $hardcovered)
        ->get()
        ->count($hardcovered);
        return $copies;
    }

    // 3. feladat
    // Bizonyos évben kiadott példányok névvel és címmel kiíratása.
    public function yearCopies($ev)
    {
        $copies = DB::table("copies as c")
        ->select("b.author", "b.title")
        ->join("books as b", "c.book_id", "=", "b.book_id")
        ->where("c.publication", "=", $ev)
        ->get();
        return $copies;
    }
    // 4. feladat
    // Raktárban lévő példányok száma.
    public function raktarban()
    {
        $copies = DB::table("copies as c")
        ->join("books as b", "c.book_id", "=", "b.book_id")
        ->where("c.status", "=", 0)
        ->orWhere("c.status", "=", 2)
        ->count();
        return $copies;
    }

    // 5. feladat
    // Bizonyos évben kiadott, bizonyos könyv raktárban lévő darabjainak a száma.
    public function raktarbanLevoKonyv($ev, $id)
    {
        $copies = DB::table("copies as c")
        ->where("publication", "=", $ev /* "and", "book_id", "=", $id */)
        ->where("book_id", "=", $id)
        ->where("status", "=", 0)
        ->orWhere("status", "=", 2)
        ->count();
        return $copies;
    }

    // 6. feladat
    // Adott könyvhöz (book_id) tartozó példányok kölcsönzési adatai (with-del és DB-vel is).
    public function konyvKolcsonAdas($id)
    {
        $copies = DB::table("copies as c")
        ->select("l.user_id", "l.start")
        ->join("lendings as l", "c.copy_id", "=", "l.copy_id")
        ->where("c.book_id", "=", $id)
        ->get();
        return $copies;
    }

    // Bejelentkezett felhasználó azon kölcsönzéseit add meg (copy_id és db), ahol egy példányt legalább db-szor (paraméteres fg) kölcsönzött ki!
    
}
