<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['verify' => true]);


// Routing for admin
Route::get('/admin/accept-users','AdminController@index');

Route::post('/admin/accept-users/{id}', 'AdminController@index');

Route::post('/admin/accept-users/{id}', 'AdminController@accept');

Route::get('/admin/create-ue', 'AdminController@createUEView');

Route::post('/admin/create-ue','AdminController@createUE');

Route::get('/admin/manage-users','AdminController@manage');

Route::post('/admin/delete-user/{id}','AdminController@deleteUser');

Route::post('/admin/promote-admin/{id}','AdminController@promoteAdmin');

Route::post('/admin/add-ue/{id}','AdminController@addUE');

//Routing for responsable d'UE
Route::get("/resp/new-grille","RespController@createGrille");

Route::get("/resp/manage-eleves","RespController@manageEleves");