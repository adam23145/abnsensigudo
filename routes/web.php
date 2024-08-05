<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AbsenceScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PklPlaceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/absence-schedule', [AbsenceScheduleController::class, 'index'])->name('absence-schedule.index');
    Route::get('/absence-schedule/create', [AbsenceScheduleController::class, 'create'])->name('absence-schedule.create');
    Route::post('/absence-schedule', [AbsenceScheduleController::class, 'store'])->name('absence-schedule.store');
    Route::get('/absence-schedule/{id}/edit', [AbsenceScheduleController::class, 'edit'])->name('absence-schedule.edit');
    Route::put('/absence-schedule/{id}', [AbsenceScheduleController::class, 'update'])->name('absence-schedule.update');
    Route::delete('/absence-schedule/{id}', [AbsenceScheduleController::class, 'destroy'])->name('absence-schedule.destroy');
    Route::get('/get-shifts/{pklPlaceId}', [AbsenceScheduleController::class, 'getShifts'])->name('get-shifts');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); // routes/web.php
    Route::post('/import-users', [UserController::class, 'importExcel'])->name('import.users');
    Route::get('/export-template', [UserController::class, 'exportTemplate'])->name('export.users');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('attendances', AttendanceController::class);
    Route::get('/kelases', [KelasController::class, 'index'])->name('kelases.index');
    Route::get('/kelases/create', [KelasController::class, 'create'])->name('kelases.create');
    Route::post('/kelases', [KelasController::class, 'store'])->name('kelases.store');
    Route::get('/kelases/{id}/edit', [KelasController::class, 'edit'])->name('kelases.edit');
    Route::put('/kelases/{id}', [KelasController::class, 'update'])->name('kelases.update');
    Route::delete('/kelases/{id}', [KelasController::class, 'destroy'])->name('kelases.destroy');
    Route::get('/jurusans', [JurusanController::class, 'index'])->name('jurusans.index');
    Route::get('/jurusans/create', [JurusanController::class, 'create'])->name('jurusans.create');
    Route::post('/jurusans', [JurusanController::class, 'store'])->name('jurusans.store');
    Route::get('/jurusans/{id}/edit', [JurusanController::class, 'edit'])->name('jurusans.edit');
    Route::put('/jurusans/{id}', [JurusanController::class, 'update'])->name('jurusans.update');
    Route::delete('/jurusans/{id}', [JurusanController::class, 'destroy'])->name('jurusans.destroy');
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers/store', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}/update', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}/delete', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/import-teachers', [TeacherController::class, 'importExcel'])->name('import.teachers');
    Route::get('/export-template-teachers', [TeacherController::class, 'exportTemplate'])->name('export.teachers');
    Route::get('/pkl-places', [PklPlaceController::class, 'index'])->name('pkl-places.index');
    Route::get('/pkl-places/create', [PklPlaceController::class, 'create'])->name('pkl-places.create');
    Route::post('/pkl-places', [PklPlaceController::class, 'store'])->name('pkl-places.store');
    Route::delete('/pkl-places/{id}', [PklPlaceController::class, 'destroy'])->name('pkl-places.destroy');
    Route::get('/shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/shifts/pklplace/{pkl_place_id?}', [ShiftController::class, 'index'])->name('shifts.index');
    Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
});
