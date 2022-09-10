<?php

use App\Models\Custom_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;

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

Route::get('/index', function () {
    if (Auth::user()) {
        return view('dashboard.index');
    } else {
        return view('auth/login');
    }
})->name('/');


Route::get('/dashboard', function () {
    /*==========Log=============*/
    $trackarray = array(
        "activityName" => "Dashboard",
        "action" => "View Dashboard -> Function: Dashboard/index()",
        "PostData" => "",
        "affectedKey" => "",
        "idUser" => Auth::user()->id,
        "username" => Auth::user()->username,
    );
    $trackarray["mainResult"] = "Success";
    $trackarray["result"] = "View Success";
    Custom_Model::trackLogs($trackarray, "all_logs");
    /*==========Log=============*/

    return view('dashboard.index');
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard2', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard2');

/*=====================================Rapid Survey=====================================*/
Route::get('linelisting', 'LineListing@index')->middleware(['auth'])->name('linelisting');
Route::get('linelisting_detail/{type?}/{id?}', 'LineListing@linelisting_detail')->middleware(['auth'])->name('linelisting_detail');
Route::get('randomized_detail/{id?}', 'LineListing@randomized_detail')->middleware(['auth'])->name('randomized_detail');
Route::get('make_pdf/{id?}', 'LineListing@make_pdf')->middleware(['auth'])->name('make_pdf');
Route::post('systematic_randomizer', 'LineListing@systematic_randomizer')->middleware(['auth'])->name('systematic_randomizer');


Route::get('data_collection', 'DataCollection@index')->middleware(['auth'])->name('data_collection');
Route::get('data_collection_detail/{type?}/{id?}', 'DataCollection@dataCollection_detail')->middleware(['auth'])->name('data_collection_detail');
Route::get('data_collection_statusdetail/{type?}/{id?}', 'DataCollection@dataCollection_statusdetail')->middleware(['auth'])->name('data_collection_statusdetail');

Route::get('app_users', 'App_Users@index')->middleware(['auth'])->name('app_users');
Route::post('app_users/addAppUsers', 'App_Users@addAppUsers')->middleware(['auth'])->name('addAppUsers');
Route::get('app_users/detail/{id?}', 'App_Users@getUserData')->middleware(['auth'])->name('getUserData');
Route::post('app_users/editAppUsers', 'App_Users@editAppUsers')->middleware(['auth'])->name('editAppUsers');
Route::post('app_users/resetPwd', 'App_Users@resetPwd')->middleware(['auth'])->name('resetPwd');
Route::post('app_users/deleteAppUsers', 'App_Users@deleteAppUsers')->middleware(['auth'])->name('deleteAppUsers');

/*=====================================Apps=====================================*/
Route::get('apps', 'Apps@index')->middleware(['auth'])->name('apps');

/*=====================================Settings=====================================*/
Route::prefix('settings')->group(function () {
    Route::get('groups', 'Settings\Group@index')->middleware(['auth'])->name('groups');
    Route::post('groups/addGroup', 'Settings\Group@addGroup')->middleware(['auth'])->name('addGroup');
    Route::get('groups/detail/{id?}', 'Settings\Group@getGroupData')->middleware(['auth'])->name('detailGroup');
    Route::post('groups/editGroup', 'Settings\Group@editGroup')->middleware(['auth'])->name('editGroup');
    Route::post('groups/deleteGroup', 'Settings\Group@deleteGroup')->middleware(['auth'])->name('deleteGroup');

    Route::get('groupSettings/{id?}', 'Settings\GroupSettings@index')->middleware(['auth'])->name('groupSettings');
    Route::get('getFormGroupData/{id?}', 'Settings\GroupSettings@getFormGroupData')->middleware(['auth'])->name('getFormGroupData');
    Route::post('fgAdd', 'Settings\GroupSettings@fgAdd')->middleware(['auth'])->name('fgAdd');

    Route::get('pages', 'Settings\Pages@index')->middleware(['auth'])->name('pages');
    Route::post('pages/addPages', 'Settings\Pages@addPages')->middleware(['auth'])->name('addPages');
    Route::get('pages/detail/{id?}', 'Settings\Pages@getPagesData')->middleware(['auth'])->name('detailPages');
    Route::post('pages/editPages', 'Settings\Pages@editPages')->middleware(['auth'])->name('editPages');
    Route::post('pages/deletePages', 'Settings\Pages@deletePages')->middleware(['auth'])->name('deletePages');

//    Route::view('dashboard_users', 'general_settings.dashboard_users')->name('dashboard_users');
    Route::get('Dashboard_Users', 'Settings\Dashboard_Users@index')->middleware(['auth'])->name('dashboard_users');
    Route::post('Dashboard_Users/addDashboardUsers', 'Settings\Dashboard_Users@addDashboardUsers')->middleware(['auth'])->name('addDashboardUsers');
    Route::get('Dashboard_Users/detail/{id?}', 'Settings\Dashboard_Users@getDashboardUsersData')->middleware(['auth'])->name('getDashboardUsersData');
    Route::post('Dashboard_Users/editDashboardUsers', 'Settings\Dashboard_Users@editDashboardUsers')->middleware(['auth'])->name('editDashboardUsers');
    Route::post('Dashboard_Users/deleteDashboardUsers', 'Settings\Dashboard_Users@deleteDashboardUsers')->middleware(['auth'])->name('deleteDashboardUsers');
    Route::post('Dashboard_Users/resetPwd', 'Settings\Dashboard_Users@resetPwd')->middleware(['auth'])->name('resetPwd');
    Route::get('Dashboard_Users/user_log_reports/{id?}', 'Settings\Dashboard_Users@user_log_reports')->middleware(['auth'])->name('user_log_reports');

});
Route::post('changePassword', 'Settings\Dashboard_Users@changePassword')->middleware(['auth'])->name('changePassword');


Route::post('checkSession', 'Check_Session@checkSession')->name('checkSession');
/*=====================================Layout Settings=====================================*/
Route::get('layout-{light}', function ($light) {
    session()->put('layout', $light);
    session()->get('layout');
    if ($light == 'vertical-layout') {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('index');
});
Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');

//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ur', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
        abort(400);
    }
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');

require __DIR__ . '/auth.php';
