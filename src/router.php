<?php

use Bidhan\Bhadhan\Http\Controllers\SchemaController;
use Illuminate\Support\Facades\Route;


Route::middleware(config('bhadhan.auth_middleware'))->group(function () {
    Route::get("bhadhan/dashboard", function () {
        return view('Bhadhan::dashboard');
    });

    Route::get("bhadhan/db-manager/schema", [SchemaController::class, 'index'])
        ->name('bhadhan-db-manager.schema');

    Route::get("bhadhan/db-manager/performance-metrics", [SchemaController::class, 'performanceMetrics'])
        ->name('bhadhan-db-manager.performance');

    Route::get("bhadhan/db-manager/sql", [SchemaController::class, 'sql'])
        ->name('bhadhan-db-manager.sql');

    Route::post("bhadhan/db-manager/sql", [SchemaController::class, 'sqlToData'])
        ->name('bhadhan-db-manager.sqlToData');
});
