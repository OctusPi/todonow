<?php

use App\Utils\Notify;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return Response()->json(Notify::warning('Destino solicitado não existe...'),404);
});
