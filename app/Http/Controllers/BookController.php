<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(){
        $books =  Book::all();
        return $books;
    }
    
    public function show($id)
    {
        $book = Book::find($id);
        return $book;
    }
    public function destroy($id)
    {
        Book::find($id)->delete();
    }
    public function store(Request $request)
    {
        $Book = new Book();
        $Book->author = $request->author;
        $Book->title = $request->title;
        $Book->save();
    }

    public function update(Request $request, $id)
    {
        $Book = Book::find($id);
        $Book->author = $request->author;
        $Book->title = $request->title;
    }

    public function bookCopies($title)
    {	
        $copies = Book::with('copy_c')->where('title','=', $title)->get();
        return $copies;
    }

    // Csoportosítsd szerzőnként a könyveket (nem példányokat) a szerzők ABC szerinti növekvő sorrendjében!
    public function konyvekCsoportositasa()
    {
        $books = DB::table("books as b")
        ->select("b.author", "b.title")
        ->orderBy("b.author")
        ->get();
        return $books;
    }

    public function szerzoMin($szam)
    {
        $books=DB::table("books as b")
        ->selectRaw("author, count(*)")
        ->groupBy("author")
        ->having("count(*)", ">=", $szam)
        ->get();
        return $books;
    }

    public function szerzoBetu($betu)
    {
        $books=DB::table("books as b")
        ->select("author")
        // ->whereRaw("author like '${betu}%'")
        ->where("author", "like", $betu."%")
        ->get();
        return $books;
    }
}