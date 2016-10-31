<?php

Route::group(['middleware' => ['web']], function () {

  Auth::routes();

  Route::get("/home", "NotepadController@index");

  Route::resource("/", "NotepadController");

  Route::delete("/{id}", "NotepadController@destroy");

  Route::put("/{id}", "NotepadController@update");

  Route::resource("/filecode", "FilecodeController");

  Route::get("/filecode/delete/{hash}", "FilecodeController@destroy");

  Route::get("/{name}", "NotepadController@show");
  Route::post("/{name}", "NotepadController@show");

  Route::post("/createfile", "NotepadController@createfile");

  Route::post("/checkpw/{name}", "NotepadController@checkpw");

  Route::get("/highlight/readfile", "FilecodeController@highlight");
  Route::post("/highlight/readfile", "FilecodeController@highlight");

  Route::get("/myfile/{name}", "NotepadController@myfile");

  Route::get("/search/s", "FilecodeController@search");

  Route::get("/readfile/{id}", "FilecodeController@show");
  Route::post("/readfile/{id}", "FilecodeController@show");

  Route::get("/savefile/{id}", "FilecodeController@savefile");
});
