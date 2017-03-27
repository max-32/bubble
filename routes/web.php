<?php

use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// middleware group AUTH
Route::group(['middleware' => 'auth'], function ()
{
    // Profile page
    Route::get('id{user}', 'ProfileController@profile')->name('profile');

    // Profile settings
    Route::get('settings', 'SettingsController@settings')->name('settings');

    // Search prefix
    Route::group(['prefix' => 'search'], function()
    {
        Route::get('create', 'SearchController@create')->name('search-create');
    });

    // Ajax namespace
    Route::group(['namespace' => 'Ajax'], function()
    {
        Route::put('profile/edit', 'EditProfileController@edit')->name('edit.profile');

        # testing
        # Route::post('upload/img', 'UploadController@img');
    });
});

// middleware group GUEST
Route::group(['middleware' => 'guest'], function ()
{
    // Signup page
    Route::get('signup', 'SignupController@signup')->name('signup');

    // Auth namespace
    Route::group(['namespace' => 'Auth'], function() {
        // OAuth url paths
        Route::get('vk/redirect', 'Vk\VkController@receiveRedirectWithCode');
        Route::get('google/redirect', 'Google\GoogleController@receiveRedirectWithCode');
        Route::get('facebook/redirect', 'Facebook\FacebookController@receiveRedirectWithCode');
        Route::get('instagram/redirect', 'Instagram\InstagramController@receiveRedirectWithCode');
    });

});

// The root/index route
Route::get('/', 'IndexController@index')->name('index');

// Loging out route
Route::get('signout', 'SignoutController@signout')->name('signout');

// About page
Route::get('about', 'AboutController@about')->name('about');