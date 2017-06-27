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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('oauth', 'OAuthController@index');
Route::get('authenticated_google', ['as'=>'authenticated_google','uses'=>'OAuthController@authenticated']);
Route::get('viewSubscriptions',['as'=>'viewSubscriptions', 'uses'=>'OAuthController@viewSubscriptions']);
// https://www.youtube.com/watch?v=RU3srXqYAO0
Route::get('viewChat',['as'=>'viewChat','uses'=>'OAuthController@viewChat']);
Route::post('fetchChats',['as'=>'fetchChats', 'uses'=>'OAuthController@fetchChats']);
Route::post('chatEndpoint',['as'=>'chatEndpoint', 'uses'=>'OAuthController@getCommentsFromYouTube']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
