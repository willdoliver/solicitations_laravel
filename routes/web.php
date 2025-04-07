<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SolicitationController;
use App\Http\Controllers\LogController;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/solicitations', [SolicitationController::class, 'index'])->name('solicitations.index');
Route::get('/solicitations/create', [SolicitationController::class, 'create'])->name('solicitations.create');
Route::post('/solicitations', [SolicitationController::class, 'store'])->name('solicitations.store');
Route::get('/solicitations/{id}', [SolicitationController::class, 'show'])->name('solicitations.show');
Route::get('/solicitations/{id}/edit', [SolicitationController::class, 'edit'])->name('solicitations.edit');
Route::put('/solicitations/{id}', [SolicitationController::class, 'update'])->name('solicitations.update');
Route::patch('/solicitations/{id}', [SolicitationController::class, 'updateStatus'])->name('solicitations.updateStatus');
Route::delete('/solicitations/{id}', [SolicitationController::class, 'destroy'])->name('solicitations.destroy');

Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
