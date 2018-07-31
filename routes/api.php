<?php
Route::middleware('jwt.validation')
    ->group(function () {
        // JWT Authentication
        Route::prefix('auth')
            ->name('auth.')
            ->group(function () {
                Route::get('me', 'AuthController@me')->name('me');
                Route::post('refresh', 'AuthController@refresh')->name('refresh');
                Route::post('logout', 'AuthController@logout')->name('logout');
        });

        Route::resource('users', 'UserController')->except(['create', 'edit']);
    });

// JWT Authentication
Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('password/recovery', 'AuthController@recoveryPassword')->name('password.recovery');
        Route::post('password/reset', 'AuthController@resetPassword')->name('password.reset');
    });
