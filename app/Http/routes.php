<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\User;
use Illuminate\Http\Response as HttpResponse;

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::post('/signup', function () {
    $credentials = Input::only('email', 'password');

    try {
        $user = User::create($credentials);
    } catch (Exception $e) {
        return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
    }

    $token = JWTAuth::fromUser($user);

    return Response::json(compact('token'));
});

Route::post('/signin', function () {
    $credentials = Input::only('email', 'password');
    $email = Input::only('email');
    if ( ! $token = JWTAuth::attempt($credentials)) {
        return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
    }
    $user = User::where('email', $email)->first();
    $roles = $user->getRoles();
    return Response::json(compact('token', 'user', 'roles'));
});

Route::get('/restricted', [
    'before' => 'jwt-auth',
    function () {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $current = User::where('email', $user->email)->first();

        return Response::json([
            'data' => [
                'name' => $current->name,
                'lastname' => $current->lastname,
                'email' => $current->email,
                'registered_at' => $current->created_at->toDateTimeString()
            ]
        ]);
    }
]);

Route::group(['domain' => 'localhost', 'prefix' => 'api'], function () {
    Route::get('/restricted', function () {
        try {
            JWTAuth::parseToken()->toUser();
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return ['data' => 'This has come from a dedicated API subdomain with restricted access.'];
    });
});
//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);
