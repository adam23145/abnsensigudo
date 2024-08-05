<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    public function checkIn(Request $request)
    {
        $nisn = $request->input('nisn');
        $time = $request->input('time');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $guru_pembimbing  = $request->input('guru_pembimbing');
        $pkl_places = $request->input('pkl_places');

        $user = User::where('nisn', $nisn)->first();
        
        if ($user) {
            $checkInTime = date('H:i:s', strtotime($time));
            $checkInDate = date('Y-m-d', strtotime($time));

            // Check if the user has already checked in today
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $checkInDate)
                ->first();

            if ($attendance) {
                return response()->json(['message' => 'Sudah Melakukan CheckIn'], 400);
            }

            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->check_in = $checkInTime;
            $attendance->date = $checkInDate;
            $attendance->check_in_latitude = $latitude;
            $attendance->check_in_longitude = $longitude;
            $attendance->guru_pembimbing = $guru_pembimbing;
            $attendance->pkl_places = $pkl_places;
            $attendance->save();

            return response()->json(['check_in' => $checkInTime], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function checkOut(Request $request)
    {
        $nisn = $request->input('nisn');
        $time = $request->input('time');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $user = User::where('nisn', $nisn)->first();

        if ($user) {
            $checkOutTime = date('H:i:s', strtotime($time));
            $checkOutDate = date('Y-m-d', strtotime($time));

            // Find the attendance record for check-in on the same date
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $checkOutDate)
                ->first();

            if ($attendance) {
                if ($attendance->check_out) {
                    return response()->json(['message' => 'Sudah Melakukan Checkout'], 400);
                }

                $attendance->check_out = $checkOutTime;
                $attendance->check_out_latitude = $latitude;
                $attendance->check_out_longitude = $longitude;
                $attendance->save();

                return response()->json(['check_out' => $checkOutTime], 200);
            } else {
                return response()->json(['message' => 'No check-in found for this user on this date'], 404);
            }
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function findByUserId($user_id)
    {
        try {
            $attendances = Attendance::where('user_id', $user_id)
                ->with('user', 'pklPlace', 'guruPembimbing')
                ->orderByDesc('created_at')
                ->take(10) // Ambil hanya 10 data terbaru
                ->get();

            if ($attendances->isEmpty()) {
                return response()->json(['message' => 'No attendance found for this user'], 404);
            }

            return response()->json(['attendances' => $attendances]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
