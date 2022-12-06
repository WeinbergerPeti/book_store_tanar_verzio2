<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//ADMIN
Route::middleware( ['admin'])->group(function () {
    //books
    Route::get('/api/books/{id}', [BookController::class, 'show']);
    Route::post('/api/books', [BookController::class, 'store']);
    Route::put('/api/books/{id}', [BookController::class, 'update']);
    Route::delete('/api/books/{id}', [BookController::class, 'destroy']);
    //copies
    Route::apiResource('/api/copies', CopyController::class);
    //queries
    Route::get('/api/book_copies/{title}', [BookController::class, 'bookCopies']);
    //view - copy
    Route::get('/copy/new', [CopyController::class, 'newView']);
    Route::get('/copy/edit/{id}', [CopyController::class, 'editView']);
    Route::get('/copy/list', [CopyController::class, 'listView']); 
});

Route::middleware(["librarian"])->group(function () {
    Route::apiResource("/users", UserController::class);
});

//SIMPLE USER
Route::middleware(['auth.basic'])->group(function () 
{    
    Route::get('/api/books', [BookController::class, 'index']);
    //user   
    Route::apiResource('/api/users', UserController::class);
    Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
    //queries
    //user lendings
    Route::get('/api/user_lendings', [LendingController::class, 'userLendingsList']);
    Route::get('/api/user_lendings_count', [LendingController::class, 'userLendingsCount']);

    // reservation
    Route::get("/api/elojegyzes", [ReservationController::class, "elojegyzes"]);

    // book
    Route::get("/api/konyvek_csoportositasa", [BookController::class, "konyvekCsoportositasa"]);
    Route::get("/api/szerzo_min/{mennyiseg}", [BookController::class, "szerzoMin"]);
    Route::get("/api/szerzo_betu/{kezdobetu}", [BookController::class, "szerzoBetu"]);

    Route::get("mail_kuldes", [MailController::class, "index"]);
    Route::get("email_kinezet", [MailController::class, "mailKinezet"]);
});
//csak a tesztelés miatt van "kint"
Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
Route::apiResource('/api/copies', CopyController::class);

// 2022.11.22 órai lekérdezések
Route::apiResource("/api/reservations", ReservationController::class);
// Route::get("/api/elojegyzes", [ReservationController::class, "elojegyzes"]);
Route::get("/api/konyvek_csoportositasa", [BookController::class, "konyvekCsoportositasa"]);
// ---- VÉGE ----

Route::get('/api/lendings', [LendingController::class, 'index']); 
Route::get('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']); // lekérdez
Route::put('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']); // minden adatot módosít
Route::patch('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']); // bizonyos adatokat módosít
Route::post('/api/lendings', [LendingController::class, 'store']); // tárolja az adatbázisba az adatokat
Route::delete('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']); // törli az adott sort
Route::get("/api/book_copies_count/{title}", [CopyController::class, "bookCopyCount"]);
Route::get("/api/kemeny_kotes/{hardcovered}", [CopyController::class, "hardCover"]);
Route::get("/api/year_copies/{year}", [CopyController::class, "yearCopies"]);
Route::get("/api/raktarban", [CopyController::class, "raktarban"]);
Route::get("/api/raktarban_levo_konyvek/{ev}/{id}", [CopyController::class, "raktarbanLevoKonyv"]);
Route::get("/api/konyv_kolcson_adas/{id}", [CopyController::class, "konyvKolcsonAdas"]);

require __DIR__.'/auth.php';
