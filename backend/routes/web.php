<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/** @var \Laravel\Lumen\Routing\Router $router */
/*
|---------------------------------------------------------------------
| Application Routes
|---------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/users', 'UserController@index'); // Get all users records
    $router->post('/users', 'UserController@addUser'); // Create new user record
    $router->get('/users/{id}', 'UserController@show'); // Get user by ID
    $router->put('/users/{id}', 'UserController@update'); // Update user record
    $router->patch('/users/{id}', 'UserController@update'); // Update user record
    $router->delete('/users/{id}', 'UserController@delete'); // Delete user record

});

// Unsecure routes
$router->get('/users', ['uses' => 'UserController@getUsers']);
    $router->get('/users', 'UserController@index'); // Get all users records
    $router->post('/users', 'UserController@addUser'); // Create new user record
    $router->get('/users/{id}', 'UserController@show'); // Get user by ID
    $router->put('/users/{id}', 'UserController@update'); // Update user record
    $router->patch('/users/{id}', 'UserController@update'); // Update user record
    $router->delete('/users/{id}', 'UserController@delete'); // Delete user record

$router->options('{any:.*}', function () {
    return response('', 200);
});

$router->post('/api/users', function (Request $request) {
    $user = User::where('username', $request->username)->first();

    if (!$user || $request->password !== $user->password) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'message' => 'Login successful',
        'user' => $user,
    ]);
});