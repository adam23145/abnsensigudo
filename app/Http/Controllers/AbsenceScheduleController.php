<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceSchedule;
use App\Models\User;
use App\Models\PklPlace;
use App\Models\Shift;

class AbsenceScheduleController extends Controller
{
    public function index()
    {
        // Fetch all absence schedules with related user and pklPlace data
        $absenceSchedules = AbsenceSchedule::with('user', 'pklPlace','shift')->get();
        // dd($absenceSchedules);
        return view('absence_schedule.index', compact('absenceSchedules'));
    }

    public function create()
    {
        // Fetch all non-admin users and all pkl places
        $users = User::where('role', '!=', 'admin')->get();
        $pklPlaces = PklPlace::all();
        return view('absence_schedule.create', compact('users', 'pklPlaces'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pkl_place_id' => 'required|exists:pkl_places,id',
            'shift_id' => 'required|exists:shifts,id', // Added validation for shift_id
        ]);

        // Check if the user already has an absence schedule for the same shift and PKL place
        $existingSchedule = AbsenceSchedule::where('user_id', $request->user_id)
            ->where('pkl_place_id', $request->pkl_place_id)
            ->exists();

        if ($existingSchedule) {
            return back()->withErrors(['user_id' => 'Pengguna ini sudah memiliki jadwal absen yang tercatat untuk shift dan tempat PKL yang dipilih.']);
        }

        // Create a new absence schedule
        AbsenceSchedule::create($request->all());

        return redirect()->route('absence-schedule.index')->with('success', 'Jadwal absen berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Find the absence schedule by ID and delete it
        $absenceSchedule = AbsenceSchedule::findOrFail($id);
        $absenceSchedule->delete();

        return redirect()->route('absence-schedule.index')->with('success', 'Jadwal absen berhasil dihapus!');
    }

    public function getShifts($pklPlaceId)
    {
        // Fetch shifts based on the selected PKL place
        $shifts = Shift::where('pkl_place_id', $pklPlaceId)->get();
        return response()->json($shifts);
    }
}
