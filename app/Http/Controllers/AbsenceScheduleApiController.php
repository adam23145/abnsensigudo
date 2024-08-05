<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceSchedule;
use App\Models\PklPlace; // Import model PklPlace

class AbsenceScheduleApiController extends Controller
{
    public function getByUserId($userId)
    {
        // Mengambil data jadwal absensi berdasarkan ID pengguna
        $absenceSchedules = AbsenceSchedule::where('user_id', $userId)
            ->with(['pklPlace.guruPembimbing', 'shift'])
            ->get();

        if ($absenceSchedules->isEmpty()) {
            // Jika tidak ada jadwal absensi untuk pengguna tersebut
            return response()->json(['message' => 'Absence schedules not found'], 404);
        }

        // Mengembalikan data jadwal absensi beserta informasi PklPlace dan Teacher dalam bentuk JSON
        return response()->json(['absence_schedules' => $absenceSchedules], 200);
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pkl_place_id' => 'required|exists:pkl_places,id',
            'shift_id' => 'required|exists:shifts,id', // Added validation for shift_id
        ]);

        // Check if the user already has an absence schedule for the same shift and PKL place
        $existingSchedule = AbsenceSchedule::where('user_id', $validatedData['user_id'])
            ->where('pkl_place_id', $validatedData['pkl_place_id'])
            ->exists();

        if ($existingSchedule) {
            return response()->json([
                'message' => 'Pengguna ini sudah memiliki jadwal absen yang tercatat untuk shift dan tempat PKL yang dipilih.'
            ], 422); // Use 422 for Unprocessable Entity if the request is semantically incorrect
        }

        // Create a new absence schedule
        AbsenceSchedule::create($validatedData);

        return response()->json([
            'message' => 'Jadwal absen berhasil ditambahkan!'
        ], 200); // Use 201 Created status code for successful creation of a resource
    }
}
