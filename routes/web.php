<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::view('/simulator', 'simulator');

// require __DIR__.'/auth.php';
