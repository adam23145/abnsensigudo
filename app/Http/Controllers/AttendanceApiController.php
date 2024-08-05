<?php

namespace App\Http\Controllers;

use App\Models\AbsenceSchedule;
use App\Models\Attendance;
use App\Models\Izin;
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
        $guru_pembimbing = $request->input('guru_pembimbing');
        $pkl_places = $request->input('pkl_places');

        $user = User::where('nisn', $nisn)->first();

        if ($user) {
            $user_id = $user->id;
            $currentTime = date('H:i:s', strtotime($time));
            $checkInDate = date('Y-m-d', strtotime($time));

            // Check if the user has already requested permission for today
            $permission = Izin::where('user_id', $user_id)
                ->where('date', $checkInDate)
                ->where(function ($query) {
                    $query->where('status', 1)
                        ->orWhereNull('status');
                })
                ->first();

            if ($permission) {
                if ($permission->status === 1) {
                    // Status 1 means permission is approved
                    return response()->json(['message' => 'Anda sudah mengajukan izin dan izin telah disetujui. Tidak perlu melakukan absensi.'], 400);
                } elseif ($permission->status === null) {
                    // Status null means permission is pending
                    return response()->json(['message' => 'Anda sudah mengajukan izin, tunggu konfirmasi.'], 400);
                }
            }


            // Check if there is already an attendance record for today
            $attendance = Attendance::where('user_id', $user_id)
                ->where('date', $checkInDate)
                ->first();

            // Fetch the user's current absence schedule
            $absenceSchedules = AbsenceSchedule::where('user_id', $user_id)
                ->with(['pklPlace.guruPembimbing', 'shift'])
                ->first();

            if ($attendance) {
                if ($attendance->check_out) {
                    return response()->json(['message' => 'Sudah Melakukan Absen Keluar'], 400);
                }
                $shiftStartTime = $absenceSchedules->shift->end_time;

                if ($currentTime <= $shiftStartTime) {
                    // Return response indicating it's not yet time to check-in
                    return response()->json(['message' => 'Belum waktunya untuk check-out. Check-out dapat dilakukan setelah pukul ' . $shiftStartTime], 400);
                }

                // Use the current time as check-in time since it's now or later than shift start time
                $checkInTime = $currentTime;
                // Update the existing attendance record with check-out details
                $attendance->check_out = $currentTime;
                $attendance->check_out_latitude = $latitude;
                $attendance->check_out_longitude = $longitude;
                $attendance->save();

                return response()->json(['check_out' => $currentTime, 'message' => 'Check Out Berhasil'], 200);
            } else {
                // Calculate the actual check-in time based on shift start time
                $shiftStartTime = $absenceSchedules->shift->start_time;

                if ($currentTime <= $shiftStartTime) {
                    // Return response indicating it's not yet time to check-in
                    return response()->json(['message' => 'Belum waktunya untuk check-in. Check-in dapat dilakukan setelah pukul ' . $shiftStartTime], 400);
                }

                // Use the current time as check-in time since it's now or later than shift start time
                $checkInTime = $currentTime;

                // Create a new attendance record for check-in
                $attendance = new Attendance();
                $attendance->user_id = $user_id;
                $attendance->check_in = $checkInTime;
                $attendance->date = $checkInDate;
                $attendance->check_in_latitude = $latitude;
                $attendance->check_in_longitude = $longitude;
                $attendance->guru_pembimbing = $guru_pembimbing;
                $attendance->pkl_places = $pkl_places;
                $attendance->status = 0;
                $attendance->save();

                return response()->json(['check_in' => $checkInTime, 'message' => 'Check In Berhasil'], 200);
            }
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function findByUserId($user_id)
    {
        try {
            $attendances = Attendance::where('user_id', $user_id)
                ->where('status', 0) // Filter where status is 0
                ->with('user', 'pklPlace', 'guruPembimbing')
                ->orderByDesc('created_at')
                ->take(10) // Retrieve only the 10 most recent records
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
