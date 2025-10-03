<?php

use App\Http\Controllers\DetailShiftController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupLiburController;
use App\Http\Controllers\GroupParticipantController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\JadwalParticipantController;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaktuLiburController;
use App\Models\WaktuLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('presensi/{api_key}/{id_device}/{id_kartu}', [PresensiController::class, 'store']);
Route::post('presensi-fp/{api_key}/{id_device}', [PresensiController::class, 'storeFp']);
Route::get('ping/{api_key}/{id_device}', [PresensiController::class, 'ping']);
Route::get('/export-report-presensi/{id}', [PresensiController::class, 'presensiExportApi']);
Route::get('/presensis', [PresensiController::class, 'getAllPresensi']);
