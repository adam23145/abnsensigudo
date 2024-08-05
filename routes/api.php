<?php

use App\Http\Controllers\AbsenceScheduleApiController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\AttendanceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\Guru\AuthGuruController;
use App\Http\Controllers\Guru\DataSiswaAbsenController;
use App\Http\Controllers\PklplaceAPiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/checkin', [AttendanceApiController::class, 'checkIn']);
Route::post('/checkout', [AttendanceApiController::class, 'checkOut']);
Route::get('/absence-schedules/{userId}', [AbsenceScheduleApiController::class, 'getByUserId']);
Route::get('attendance/{user_id}', [AttendanceApiController::class, 'findByUserId']);
Route::get('/pklplace', [PklplaceAPiController::class, 'pklplace']);
Route::get('/pklplace/shifts/{pklPlaceId}', [PklplaceApiController::class, 'getShifts']);
Route::get('/pkl-places/{id}', [PklplaceAPiController::class, 'getGuruPembimbingId']);
Route::post('absence-schedules', [AbsenceScheduleApiController::class, 'store']);
Route::post('/loginguru', [AuthGuruController::class, 'login']);
Route::get('/attendances/pendamping/{id_pendamping}', [DataSiswaAbsenController::class, 'getByPendampingId']);
Route::get('/pkl-places/guru-pembimbing/{guru_pembimbing_id}', [PklplaceAPiController::class, 'getByGuruPembimbingId']);
Route::post('izin', [IzinController::class, 'store']);
Route::get('perizinan', [IzinController::class, 'index']);
Route::patch('perizinan/{id}', [IzinController::class, 'update']);
Route::get('/perizinan/getdatawithid', [IzinController::class, 'getdatawithid']);
Route::get('attendances/pendamping/{id}/download', [IzinController::class, 'downloadExcel']);

