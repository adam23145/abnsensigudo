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

        $existingPermission = Izin::where('user_id', $userId)
            ->whereDate('date', $date)
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhereNull('status');
            })
            ->exists();

        if ($existingPermission) {
            return response()->json([
                'error' => 'Anda sudah mengajukan izin untuk tanggal ini.'
            ], 401); // Bad Request
        }

        Attendance::where('user_id', $userId)
            ->whereDate('date', $date)
            ->delete();

        // Store the photo
        $photoPath = $request->file('photo')->store('izin_photos', 'public');

        Attendance::create([
            'user_id' => $userId,
            'check_in' => null,
            'check_out' => null,
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

        // $exitingattendence = Attendance::where('user_id', $userId)
        //     ->whereDate('date', $date)
        //     ->where('check_out', '!=', null)
        //     ->exists();

        // if ($exitingattendence) {
        //     return response()->json([
        //         'error' => 'Anda sudah melakukan absen untuk hari ini.'
        //     ], 401); // Bad Request
        // }
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
            ->orderBy('id', 'desc') // Mengurutkan berdasarkan tanggal terbaru
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
            ->orderBy('id', 'desc') // Mengurutkan berdasarkan tanggal terbaru
            ->get();

        // Kembalikan respons JSON
        return response()->json($perizinan);
    }

    // In IzinController.php
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|integer',
        ]);

        // Find the Izin record by ID
        $izin = Izin::find($id);
        if (!$izin) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Get the user_id and date from the Izin record
        $userId = $izin->user_id;
        $date = $izin->date;

        // Delete Attendance records for the user_id and date
        Attendance::where('user_id', $userId)
            ->whereDate('date', $date)
            ->delete();

        // Update the status of the Izin record
        $izin->status = $request->status;
        $izin->save();

        // Return the updated Izin record as JSON
        return response()->json($izin);
    }

    public function downloadExcel($id_pkl_places)
    {
        return Excel::download(new AttendanceExport($id_pkl_places), 'attendance_data.xlsx');
    }
}
