<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // fetch all users
    // binding value
    // $users = DB::select("select * from users where email = ?", ['qfzhang18@hotmail.com']);
    // $users = DB::select("select * from users");
    // use query builder
    // $users = DB::table('users')->get();
    // $users = DB::table('users')->first();
    // $users = DB::table('users')->where('id',1)->get();
    // Eloquent
    // $users = User::where('id',1)->first();

    // create new user
    // $user = DB::insert('insert into users (name, email, password) values (?, ?, ?)', [
    //     'Sarthak',
    //     'Sarthak@bitfumes.com',
    //     'password'
    // ]);
    // use query builder
    // $user = DB::table('users')->insert([
    //     'name'=>'Sarthak',
    //     'email' => 'kayla@example.com',
    //     'password'=> 'password'
    // ]);
    // Eloquent
    // $user = User::create([
    //     'name'=>'Sarthak1',
    //     'email' => 'Sarthak@bitfumes.com',
    //     'password'=> 'password'
    // ]);

    // update a user
    // $user = DB::update('update users set email = "abc@gmail.com" where id = 2');  
    // $user = DB::update('update users set email=? where id= ?', [
    //     'Sarthak@bitfumes.com',
    //     2,
    // ]);
    // use query builder
    // $user = DB::table('users')->where('id', 1)->update([
    //     'email' => 'qfzhang18@hotmail.com',
    // ]);
    // Eloquent
    // $user = User::where('id',6)->first();
    // $user = User::find(6);
    // $user = User::where('id',6)->update([
    //     'email'=>'Sarthak@bitfumes.com'
    // ]);
    // $user->update([
    //     'email'=>'Sarthak1@bitfumes.com',
    // ]);

    // delete a user
    // $user = DB::delete("delete from users where id=?", [
    //     2
    // ]);
    // use query builder
    // $user = DB::table('users')->where('id',3)->delete();
    // eloquent
    // $user = User::find(6);
    // $user->delete();
    // $users = User::all();
    // dd($users);
    // dd($user);
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::post('/profile/avatar/ai', [AvatarController::class, 'generate'])->name('profile.avatar.ai');
});

require __DIR__ . '/auth.php';


Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user = User::firstOrCreate(['email' => $user->email], [
        // 'name' => $user->name || $user->nickname,
        'name' => $user->name ? $user->name : $user->nickname,
        'password' => 'password',
    ]);

    Auth::login($user);

    return redirect('/dashboard');
    // dd($user); //->token
});

Route::middleware('auth')->group(function () {
    // Route::resource('/', TicketController::class); 
    Route::resource('/ticket', TicketController::class); //this will include all curd
    // name route with ->name(''name)
    // Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    // Route::post('/ticket/create', [TicketController::class, 'store'])->name('ticket.store');
    // Route::get('ticket/create', function(){
    //     return view('ticket.create');
    // });

});



// Route::get('/openai', function () {

//     // $result = OpenAI::completions()->create([
//     //     'model' => 'text-davinci-003',
//     //     'prompt' => 'PHP is',
//     // ]);
//     $result = OpenAI::images()->create([
//         // "prompt" => "A cute baby bird " . auth()->user()->name,
//         // "prompt" => "create avata for a lady , chinese born, with cool style animated in tech with  " . auth()->user()->name,
//         "prompt" => "create avata for a lady age 49s, chinese, happy, dark hair, looks young cool style animated in tech with  " . auth()->user()->name,
//         "n" => 1,
//         "size" => "256x256",
//     ]);
//     // dd($result);
//     return response(['url' => $result->data[0]->url]);
//     // dd($result->data[0]->url);
//     // echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
// });
