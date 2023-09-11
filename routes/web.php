<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return bcrypt('inikatasandi');
});

Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage")->name('admin.loginPage');
    Route::post('login', "AdminController@login")->name('admin.login');
    Route::get('logout', "AdminController@logout")->name('admin.logout');

    Route::group(['middleware' => "Admin"], function () {
        Route::get('dashboard', "AdminController@dashboard")->name('admin.dashboard');
        Route::get('user', "AdminController@user")->name('admin.user');
        Route::get('organizer', "AdminController@organizer")->name('admin.organizer');
        Route::get('event', "AdminController@event")->name('admin.event');

        Route::group(['prefix' => "package"], function () {
            Route::get('/', "AdminController@package")->name('admin.package');
            Route::post('create', "PackageController@create")->name('package.create');
            Route::post('update', "PackageController@update")->name('package.update');
            Route::post('delete', "PackageController@delete")->name('package.delete');
        });

        Route::group(['prefix' => "city"], function () {
            Route::get('/', "AdminController@city")->name('admin.city');
            Route::post('create', "CityController@create")->name('city.create');
            Route::post('update', "CityController@update")->name('city.update');
            Route::post('delete', "CityController@delete")->name('city.delete');
        });

        Route::group(['prefix' => "topic"], function () {
            Route::get('/', "AdminController@topic")->name('admin.topic');
            Route::post('create', "TopicController@create")->name('topic.create');
            Route::post('update', "TopicController@update")->name('topic.update');
            Route::post('delete', "TopicController@delete")->name('topic.delete');
        });

        Route::group(['prefix' => "category"], function () {
            Route::get('/', "AdminController@category")->name('admin.category');
            Route::post('create', "CategoryController@create")->name('category.create');
            Route::post('update', "CategoryController@update")->name('category.update');
            Route::post('delete', "CategoryController@delete")->name('category.delete');
            Route::get('{id}/priority/{action}', "CategoryController@priority")->name('category.priority');
        });
    });
});