<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "HomeController@index");

Route::get("/home", "HomeController@index")->name("home");

Route::get("/chi_siamo", "ChiSiamoController@index")->name("chi_siamo");

Route::get("/contattaci", "ContattaciController@index")->name("contattaci");

Route::get("/login", "LoginController@index")->name("login");
Route::post("/login", "LoginController@check");
Route::get("/logout", "LoginController@logout")->name("logout");

Route::get("/musei", "MuseiController@index")->name("musei");

Route::get("/signup", "SignupController@index")->name("signup");
Route::get("/signup/cities/{sigla}", "SignupController@citiesList")->name("signup.cities_list");
Route::post("/signup", "SignupController@signup");
Route::post("/signup/check_page_1", "SignupController@checkPage1")->name("signup.check_page_1");
Route::post("/signup/check_page_2", "SignupController@checkPage2")->name("signup.check_page_2");

Route::get("/personal_area", "PersonalAreaController@index")->name("personal_area"); 

Route::get("/gestione_dati_museo", "GestioneDatiMuseoController@index")->name("gestione_dati_museo");
Route::post("/gestione_dati_museo", "GestioneDatiMuseoController@check")->name("gestione_dati_museo.check");

Route::get("/gestione_opere", "GestioneOpereController@index")->name("gestione_opere");
Route::get("/gestione_opere/delete/{id}", "GestioneOpereController@delete")->name("gestione_opere.delete");
Route::post("/gestione_opere/check", "GestioneOpereController@check")->name("gestione_opere.check");

Route::get("/backup_opere", "BackupOpereController@index")->name("backup_opere");
Route::get("/backup_opere/backup", "BackupOpereController@backup")->name("backup_opere.backup");

Route::get("/musei", "MuseiController@index")->name("musei");
Route::get("/musei/generation", "MuseiController@generation")->name("musei.generation");

Route::get("/api/weather/{id}", "ApiController@weather")->name("api.weather");
Route::get("/api/map", "ApiController@map")->name("api.map");

Route::get("/informazioni_museo", "InformazioniMuseoController@index")->name("informazioni_museo");
Route::get("/informazioni_museo/artworks", "InformazioniMuseoController@artworks")->name("informazioni_museo.artworks");

?>