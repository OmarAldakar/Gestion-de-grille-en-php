<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Grille;
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
Route::get("/resp/new-grille/{id}","RespController@createGrilleView");

Route::post("/resp/new-grille/{id}","RespController@createGrille");

Route::get("/resp/manage-eleves/{id}","RespController@manageEleves");

Route::get('/resp/{id}','RespController@index');

Route::post("/resp/new-exercice/{id}","RespController@addExercice");

Route::post("/resp/delete-exercice/{id}/{ex_id}","RespController@deleteExercice");

Route::get("/resp/detail-grille/{id}/{ex_id}/{grille_id}","RespController@detailGrille");

Route::post("/resp/associate/{id}/{ex_id}","RespController@associate");

Route::post("/resp/disociate/{id}/{grille_id}","RespController@disassociate");

Route::post("/resp/create-eleve/{id}","RespController@addEleve");

Route::post("/resp/delete/{id}/{eleve_id}","RespController@removeEleve");

Route::post("/resp/importEleve/{id}","RespController@importEleve");

Route::post("/resp/associate-correcteur/{id}/{ex_id}/{grille_id}","RespController@associateCorrecteur");

Route::post("/resp/associate-student/{id}/{ex_id}/{grille_id}/{user_id}","RespController@associateStudent");
// Route vue grille
Route::get("/grille/{grille_id}", function($id) {
    return view ('responsable.grille')->with('grille',Grille::find($id));
});