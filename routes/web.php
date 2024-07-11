<?php 



use App\Http\Controllers\VlsmController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('vlsm.index');
});

Route::post('/calculate', [VlsmController::class, 'calculate'])->name('calculate');


?>