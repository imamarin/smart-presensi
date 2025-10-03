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
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes (tanpa middleware)
Route::get('/', function() {
    return view('auth');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Semua route di bawah hanya untuk user yang sudah login dan punya role admin/operator
Route::middleware(['auth', 'checkRole:admin,operator'])->group(function () {

    Route::post('/auth', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/me', [UserController::class, 'me'])->name('me');
    Route::get('/me/update', [UserController::class, 'meUpdate'])->name('me.update');
    Route::resource('user', UserController::class);

    Route::get('/participant/import', [ParticipantsController::class, 'importView'])->name('participant.import');
    Route::post('/participant/import', [ParticipantsController::class, 'import'])->name('participant.import.data');
    Route::resource('participant', ParticipantsController::class);

    Route::resource('group', GroupsController::class);
    Route::delete('/groupParticipant/destroy', [GroupParticipantController::class, 'destroy'])->name('groupParticipant.destroy');
    Route::resource('groupParticipant', GroupParticipantController::class)->except('destroy');

    Route::get('group/{id}/addParticipant', [GroupsController::class, 'addParticipant'])->name('group.addParticipant');
    Route::get('group/{id}/removeParticipant', [GroupsController::class, 'removeParticipant'])->name('group.removeParticipant');

    Route::resource('jamKerja', JamKerjaController::class);
    Route::resource('shift', ShiftController::class);

    Route::get('shift/{shift_id}/createDetailShift', [ShiftController::class, 'createDetailShift'])->name('shift.createDetailShift');

    Route::resource('detailShift', DetailShiftController::class);
    Route::resource('jadwalParticipant', JadwalParticipantController::class)->except(['edit', 'destroy', 'update', 'show']);
    Route::get('jadwalParticipant/create/{id_shift}', [JadwalParticipantController::class, 'create'])->name('jadwalParticipant.create');
    Route::get('jadwalParticipant/remove/{id_shift}', [JadwalParticipantController::class, 'remove'])->name('jadwalParticipant.remove');
    Route::get('jadwalParticipant/{id_shift}/show', [JadwalParticipantController::class, 'show'])->name('jadwalParticipant.show');
    Route::delete('jadwalParticipant/{id_shift}/destroy', [JadwalParticipantController::class, 'destroy'])->name('jadwalParticipant.destroyItem');
    Route::resource('presensi', PresensiController::class);

    Route::resource('waktuLibur', WaktuLiburController::class);
    Route::delete('groupLibur/{id_group}/{id_waktu_libur}/destroy', [GroupLiburController::class, 'destroy'])->name('groupLibur.destroyItem');
    // Route::resource('groupLibur', GroupLiburController::class);

    Route::get('/export-participant', [ParticipantsController::class, 'exportAll'])->name('export.participant');
    Route::get('/export-participant/{id}', [ParticipantsController::class, 'exportByGroup'])->name('export.participant.group');
    Route::get('/export-report-presensi/{id}', [PresensiController::class, 'presensiExport'])->name('export.presensi.group');

    Route::get('/test/{id}', [PresensiController::class, 'test']);
});

// Route device hanya untuk admin
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::resource('device', DeviceController::class);
    Route::post('/verify-password', [DeviceController::class, 'verifyPassword'])->name('verify.password');

});
