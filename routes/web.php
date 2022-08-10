<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

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

// COMMON RESOURCE ROUTES:









//Show all lsiting
Route::get('/', [ListingController::class, 'index']);

//Show form to create new listing
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

//Store new listing
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

//Show form to edit listing
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

//Update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

//Delete listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

//Show single lsiting
Route::get('/listings/{listing}', [ListingController::class, 'show']);


// Show register create form

// show register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// store users form details
Route::post('/users', [UserController::class, 'store']);

// show Logout form
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Login In user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);
