<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BackupsController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\CouplesController;
use App\Http\Controllers\FamilyActionsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserMarriagesController;
use App\Http\Controllers\UsersController;
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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [UsersController::class, 'search']);

    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'index')->name('home');
        Route::get('profile', 'index')->name('profile');
    });
    Route::controller(FamilyActionsController::class)->prefix('family-actions/{user}')->group(function () {
        Route::post('set-father', 'setFather')->name('family-actions.set-father');
        Route::post('set-mother', 'setMother')->name('family-actions.set-mother');
        Route::post('add-child', 'addChild')->name('family-actions.add-child');
        Route::post('add-wife', 'addWife')->name('family-actions.add-wife');
        Route::post('add-husband', 'addHusband')->name('family-actions.add-husband');
        Route::post('set-parent', 'setParent')->name('family-actions.set-parent');
    });
    
    Route::controller(UsersController::class)->prefix('users/{user}')->group(function () {
        Route::get('profile-search', 'search')->name('users.search');
        Route::get('', 'show')->name('users.show');
        Route::get('edit', 'edit')->name('users.edit');
        Route::patch('', 'update')->name('users.update');
        Route::get('chart', 'chart')->name('users.chart');
        Route::get('tree', 'tree')->name('users.tree');
        Route::get('death', 'death')->name('users.death');
        Route::patch('photo-upload', 'photoUpload')->name('users.photo-upload');
        Route::delete('', 'destroy')->name('users.destroy');
    });

    Route::get('users/{user}/marriages', [UserMarriagesController::class, 'index'])->name('users.marriages');

    Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    /**
     * Couple/Marriages Routes
     */
    Route::controller(CouplesController::class)->prefix('couples/{couple}')->group(function () {
        Route::get('', 'show')->name('couples.show');
        Route::get('edit', 'edit')->name('couples.edit');
        Route::patch('', 'update')->name('couples.update');
    });
    
    Route::controller(ChangePasswordController::class)->prefix('password')->group(function () {
        Route::get('change', 'show')->name('password_change');
        Route::post('change', 'update')->name('password_update');
    });
    
});

/**
 * Admin only routes
 */
Route::group(['middleware' => 'admin'], function () {
    /**
     * Backup Restore Database Routes
     */
    Route::controller(BackupsController::class)->prefix('backups')->group(function () {
        Route::post('upload', 'upload')->name('backups.upload');
        Route::post('{fileName}/restore', 'restore')->name('backups.restore');
        Route::get('{fileName}/dl', 'download')->name('backups.download');
    });
    
    Route::resource('backups', BackupsController::class);
});
