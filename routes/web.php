<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');



Route::group(['prefix'=>'account'],function(){
    //Guest Route
    Route::group(['middleware'=>'guest'],function(){
        Route::get('/register',[AccountController::class,'registration'])->name('account.registration');
        Route::post('/process-register',[AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('/login',[AccountController::class,'login'])->name('account.login');
        Route::post('/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });
    //Auth Route
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
        Route::put('/updateProfile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-job',[AccountController::class,'createJob'])->name('account.createJob');
        Route::post('/save-job',[AccountController::class,'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs',[AccountController::class,'myJob'])->name('account.myJob');
        Route::get('/my-jobs/edit/{jobId}',[AccountController::class,'editJob'])->name('account.editJob');
        Route::post('/update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
        Route::post('/delete-job',[AccountController::class,'destroy'])->name('account.destroyJob');
        Route::post('/my-jobs/update/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
        Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
    });
});
