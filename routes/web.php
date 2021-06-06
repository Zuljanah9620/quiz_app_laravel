<?php

use Illuminate\Support\Facades\Auth;
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
    return redirect(route('home'));
});

Auth::routes([
	'register' => false,
	'reset'	   => false,
	'verify'   => false
]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/getUsers/','HomeController@getUsers')->name('home.getUsers');
Route::post('/home/update', 'HomeController@update')->name('home.update');
Route::post('/home/store', 'HomeController@store')->name('home.store');
Route::get('/home/{id}/edit', 'HomeController@edit');

Route::group(['prefix' => '/user'], function(){
	Route::get('/', 'UserController@index')->name('user');
	Route::get('/getUsers', 'UserController@getUsers')->name('user.getUsers');
	Route::post('/store', 'UserController@store')->name('user.store');
	Route::post('/update', 'UserController@update')->name('user.update');
	Route::get('/{id}/edit', 'UserController@edit');
	Route::get('/destroy/{id}', 'UserController@destroy');
});

Route::group(['prefix' => '/report'], function(){
	Route::get('/', 'ReportController@index')->name('report');
	Route::get('/getReports', 'ReportController@getReports')->name('report.getReports');
	Route::get('/{id}/edit', 'ReportController@edit');
	Route::post('/update', 'ReportController@update')->name('report.update');
});


Route::group(['prefix' => '/category'], function(){
	Route::get('/', 'CategoryController@index')->name('category');
	Route::get('/getCategories', 'CategoryController@getCategories')->name('category.getCategories');
	Route::post('/store', 'CategoryController@store')->name('category.store');
	Route::post('/update', 'CategoryController@update')->name('category.update');
	Route::get('/{id}/edit', 'CategoryController@edit');
	Route::get('/destroy/{id}', 'CategoryController@destroy');
});

Route::get('/question', 'QuestionController@index')->name('question.index');

Route::group(['prefix' => '/question'], function(){
	Route::get('/', 'QuestionController@index')->name('question.index');
	Route::get('/{id}', 'QuestionController@questionIndex')->name('question.questionIndex');
	Route::get('/getQuestions/{id}', 'QuestionController@getQuestions')->name('question.getQuestions');
	Route::post('/store', 'QuestionController@store')->name('question.store');
	Route::post('/store_csv', 'QuestionController@store_csv')->name('question.store_csv');
	Route::post('/update', 'QuestionController@update')->name('question.update');
	Route::get('/{id}/edit', 'QuestionController@edit');
	Route::get('/{id}/show', 'QuestionController@show');
	Route::get('/destroy/{id}', 'QuestionController@destroy');
});

Route::get('/notification', 'NotificationController@index')->name('notification.index');
Route::post('/notification/send', 'NotificationController@send')->name('notification.send');

Route::get('/appconfig', 'AppConfigController@index')->name('appconfig');
Route::post('/appconfig', 'AppConfigController@update')->name('appconfig.update');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::group(['prefix' => '/api'], function(){
	Route::get('/ads', 'ApiController@getAdIds');
	Route::get('/category', 'ApiController@getCategories');
	Route::get('/question', 'ApiController@getQuestions');
	Route::post('report', 'ApiController@reportFromApp');
});