<?php

use Fadllabanie\EnvEditor\Http\Controllers\EnvEditorAuthController;
use Fadllabanie\EnvEditor\Http\Controllers\EnvEditorController;
use Illuminate\Support\Facades\Route;



// Route to display the .env editor form
Route::get('/env-editor/edit', [EnvEditorController::class, 'index'])->name('env.edit');

// Route to handle the form submission to update the .env file
Route::post('/env-editor/update', [EnvEditorController::class, 'update'])->name('env.update');


// Route to show the login form
Route::get('/env-editor/login', [EnvEditorAuthController::class, 'showLoginForm'])
->name('env.login')
->middleware('web');

// Route to handle the login form submission
Route::post('/env-editor/login', [EnvEditorAuthController::class, 'login'])
->middleware('throttle:5,1')
->name('env.login.submit')->middleware('web');
// Route to handle logout
Route::post('/env-editor/logout', [EnvEditorAuthController::class, 'logout'])->name('env.logout');