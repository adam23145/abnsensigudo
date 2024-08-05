<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\PklPlace;

class ShiftController extends Controller
{
    public function index(Request $request, $pkl_place_id = null)
    {
        if ($pkl_place_id) {
            $shifts = Shift::with('pklPlace')->where('pkl_place_id', $pkl_place_id)->get();
        } else {
            return redirect()->route('pkl-places.index')->with('error', 'Data tidak ada.');
        }

        $pklPlaces = PklPlace::all();
        return view('shifts.index', compact('shifts', 'pklPlaces', 'pkl_place_id'));
    }


    public function create()
    {
        $pklPlaces = PklPlace::all();
        return view('shifts.create', compact('pklPlaces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'pkl_place_id' => 'required|exists:pkl_places,id',
        ]);

        // Find the last shift for the same PKL place
        $lastShift = Shift::where('pkl_place_id', $request->pkl_place_id)->orderBy('id', 'desc')->first();

        if ($lastShift) {
            // Extract the number from the last shift's name
            preg_match('/\d+$/', $lastShift->shift_name, $matches);
            $nextNumber = $matches ? ((int)$matches[0] + 1) : 1;
        } else {
            $nextNumber = 1;
        }

        // Automatically name the shift
        $shiftName = "Shift " . $nextNumber;

        $shift = new Shift();
        $shift->shift_name = $shiftName;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->pkl_place_id = $request->pkl_place_id;

        $shift->save();

        return redirect()->route('pkl-places.index')->with('success', 'Shift berhasil ditambahkan.');
    }
    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('pkl-places.index')->with('success', 'Shift berhasil dihapus.');
    }
}
