<?php
use App\Http\Controllers\Tasks;
use App\Http\Controllers\Users;
use App\Utils\Notify;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use Illuminate\Http\Request;


Route::prefix('/auth')->controller(Authentication::class)->group(function () {
    Route::post('', 'login');
    Route::post('/newuser', 'newuser');
    Route::get('/logout', 'logout');
    Route::get('/check', 'check');
});


//authenticaded
Route::middleware('auth:sanctum')->group(function () {

    $common = function (string $prefix, string $controller) {
        Route::prefix($prefix)->controller($controller)->group(function () {
            Route::post('/save', 'save');
            Route::post('/list/{page?}/{total_page?}', 'list');
            Route::get('/destroy/{id}', 'delete');
            Route::get('/details/{id}', 'details');
            Route::get('', 'index');
        });
    };

    $common('/users', Users::class);
    $common('/tasks', Tasks::class);

});

// fallback 404
Route::fallback(function () {
    return response()->json(Notify::warning('Destino solicitado não existe...'), 404);
});
