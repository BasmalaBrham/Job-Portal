<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashBoardController;
use App\Http\Controllers\admin\JobAdminController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController as ControllersJobController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{id}',[JobsController::class,'detail'])->name('jobDetail');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobsController::class,'saveJob'])->name('saveJob');


//admin route
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'checkRole']], function() {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/jobs',[JobAdminController::class ,'index'])->name('admin.job');
    Route::get('/jobs/edit/{id}',[JobAdminController::class ,'edit'])->name('admin.job.edit');
    Route::put('/jobs/{id}',[JobAdminController::class ,'update'])->name('admin.job.update');
    Route::delete('/jobs/{id}', [JobAdminController::class, 'destroy'])->name('admin.jobs.destroy');

});


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
        Route::get('/my-job-applications',[AccountController::class,'myJobApplication'])->name('account.myJobApplication');
        Route::post('/remove-job-application',[AccountController::class,'removeJob'])->name('account.removeJob');
        Route::get('/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('/remove-saved-job',[AccountController::class,'removeSavedJob'])->name('account.removeSavedJob');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
        Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
    });
});
