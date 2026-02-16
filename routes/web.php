<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OAuthClientController;
use App\Http\Controllers\DashboardImageController;
use App\Http\Controllers\NanoVisualToolsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Login route - redirects to OAuth login
Route::get('/login', function () {
    return redirect()->route('login.aisite');
})->name('login');

// Start login with AISITE (your main app)
Route::get('/login/aisite', [OAuthClientController::class, 'redirectToProvider'])
    ->name('login.aisite');

// Callback URL AISITE redirects back to, with ?code=...
Route::get('/oauth/callback', [OAuthClientController::class, 'handleProviderCallback'])
    ->name('oauth.callback');

// Simple protected dashboard
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Proxy endpoint for dashboard image generation
Route::middleware('auth')->post('/dashboard/image', [DashboardImageController::class, 'generate'])
    ->name('api.image.generate');

// ==================================
// NANO VISUAL TOOLS ROUTES
// ==================================
// These routes provide a client interface for the Nano Visual Tools API
Route::middleware('auth')->group(function () {
    // Main visual tools page
    Route::get('/nano-visual-tools', [NanoVisualToolsController::class, 'index'])
        ->name('nano.visual.tools');
    
    // API proxy endpoints (these call the main AISITE API)
    Route::get('/api/nano-visual-tools', [NanoVisualToolsController::class, 'getTools'])
        ->name('api.nano.visual.tools.get');
    
    Route::post('/api/nano-visual-tools/run', [NanoVisualToolsController::class, 'runTool'])
        ->name('api.nano.visual.tools.run');
    
    Route::get('/api/nano-visual-tools/images', [NanoVisualToolsController::class, 'getImages'])
        ->name('api.nano.visual.tools.images');
    
    Route::post('/api/nano-visual-tools/regenerate', [NanoVisualToolsController::class, 'regenerate'])
        ->name('api.nano.visual.tools.regenerate');
});
