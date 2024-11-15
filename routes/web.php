<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Token\Parser;
use Psr\Http\Message\ServerRequestInterface;

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
    return view('welcome');
});

Route::get('/login', function (Request $request) {

    $accessToken = Request::capture()->cookies->get('access_token');
        
    if ($accessToken) {
        $request->headers->set('Authorization', 'Bearer ' . $accessToken);

        if (Auth::guard('api')->check()) {
            return redirect()->to($request->query('redirect_uri') ?? env('DEFAULT_SISTEM_URL'));
        }
    }


    if($request->has('error')) {
        return view('login', ['redirect_uri' => $request->query('redirect_uri'), 'error' => $request->query('error')]);
    }
    
    return view('login', ['redirect_uri' => $request->query('redirect_uri')]);
})->name('login');
