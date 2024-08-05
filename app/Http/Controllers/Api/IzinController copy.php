<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Exports\AttendanceExport;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;

class IzinController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'photo' => 'required|image|max:2048',
        ]);

        // Get the user_id, date, guru_pembimbing_id, and pkl_place_id from the request
        $userId = $request->user_id;
        $date = $request->date;
        $guruPembimbingId = $request->guru_pembimbing_id;
        $pklPlaceId = $request->pkl_place_id;

        // Check if a permission request already exists for the same user on the same date
        $existingPermission = Izin::where('user_id', $userId)
            ->whereDate('date', $date)
            ->exists();

        if ($existingPermission) {
            return response()->json([
                'error' => 'Anda sudah mengajukan izin untuk tanggal ini.'
            ], 401); // Bad Request
        }

        // $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);

        // Check if the user has already recorded attendance on the given date
        $existingPermissionAttendance = Attendance::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->exists();

        if ($existingPermissionAttendance) {
            return response()->json([
                'error' => 'Anda sudah absen untuk tanggal ini.'
            ], 402); // Bad Request
        }

        // Store the photo
        $photoPath = $request->file('photo')->store('izin_photos', 'public');

        Attendance::create([
            'user_id' => $userId,
            'check_in' => null,
            'date' => $date,
            'check_in_latitude' => null,
            'check_in_longitude' => null,
            'guru_pembimbing' => $guruPembimbingId,
            'pkl_places' => $pklPlaceId,
            'status' => 1,
        ]);

        // Create a new permission record
        $izin = Izin::create([
            'user_id' => $userId,
            'date' => $date,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'guru_pembimbing_id' => $guruPembimbingId, // Simpan guru_pembimbing_id
            'pkl_place_id' => $pklPlaceId,             // Simpan pkl_place_id
        ]);

        // Return the created record as JSON
        return response()->json($izin, 201);
    }

    public function index(Request $request)
    {
        // Get the guru_pembimbing_id from the request header
        $guruPembimbingId = $request->header('guru_pembimbing_id');

        // Fetch perizinan records with user information where status is null, ordered by date in descending order
        $perizinan = Izin::with('user')
            ->whereNull('status')
            ->where('guru_pembimbing_id', $guruPembimbingId) // Filter by guru_pembimbing_id
            ->orderBy('date', 'desc') // Mengurutkan berdasarkan tanggal terbaru
            ->get();

        // Return JSON response
        return response()->json($perizinan);
    }


    public function getdatawithid(Request $request)
    {
        // Ambil user_id dari query parameter
        $userId = $request->query('user_id');

        // Fetch perizinan records dengan informasi user berdasarkan user_id, ordered by date in descending order
        $perizinan = Izin::with('user')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc') // Mengurutkan berdasarkan tanggal terbaru
            ->get();

        // Kembalikan respons JSON
        return response()->json($perizinan);
    }

    // In IzinController.php
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer',
        ]);

        $izin = Izin::find($id);
        if (!$izin) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Update the status based on the provided value
        $izin->status = $request->status;
        $izin->save();

        return response()->json($izin);
    }
    public function downloadExcel($id_pkl_places)
    {
        return Excel::download(new AttendanceExport($id_pkl_places), 'attendance_data.xlsx');
    }
}
