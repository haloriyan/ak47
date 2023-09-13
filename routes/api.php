<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => "user"], function () {
    Route::post('login', "UserController@login");
    Route::post('logout', "UserController@logout");
    Route::post('register', "UserController@register");
    Route::post('auth', "UserController@auth");
    Route::post('profile', "UserController@profile");
    Route::post('otp', "OtpController@auth");
    Route::post('ticket', "UserController@ticket");

    Route::group(['prefix' => "transaction"], function () {
        Route::post('checkout', "PurchaseController@checkout");
        Route::post('/', "PurchaseController@myTransaction");
        
        Route::group(['prefix' => "{id}"], function () {
            Route::post('holder', "PurchaseController@setHolder");
            Route::post('/', "PurchaseController@transactionDetail");
        });
    });
});

Route::group(['prefix' => "organizer"], function () {
    Route::post('create', "OrganizerController@create");
    Route::post('/', "OrganizerController@get");

    Route::group(['prefix' => "{id}"], function () {
        Route::post('/profile', "OrganizerController@profile");
        Route::post('/profile/update', "OrganizerController@updateProfile");
        Route::post('/upgrade', "OrganizerController@upgrade");
        Route::group(['prefix' => "event"], function () {
            Route::post('create', "EventController@create");
            Route::post('/', "OrganizerController@event");
            Route::post('{eventID}', "EventController@get");
            Route::post('{eventID}/delete', "EventController@delete");
        });

        Route::group(['prefix' => "team"], function () {
            Route::post('/', "TeamController@get");
        });
    });
});

Route::group(['prefix' => "package"], function () {
    Route::get('/', "PackageController@get");
});

Route::group(['prefix' => "category"], function () {
    Route::get('/', "CategoryController@get");
    Route::get('{name}/event', "CategoryController@getEvent");
});
Route::group(['prefix' => "event"], function () {
    Route::post('featured', "EventController@featured");
    Route::get('{id}', "EventController@detail");
    Route::post('search', "EventController@search");
});
Route::group(['prefix' => "city"], function () {
    Route::get('/', "CityController@get");
    Route::get('{name}/event', "CityController@event");
});
Route::group(['prefix' => "topic"], function () {
    Route::get('/', "TopicController@get");
});

Route::group(['prefix' => "callback"], function () {
    Route::post('invoice', "PackageController@invoiceCallback");
});