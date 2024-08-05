<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceSchedule;
use App\Models\User;
use App\Models\PklPlace;
use App\Models\Shift;

class PklplaceApiController extends Controller
{
    public function pklplace()
    {
        $pklPlaces = PklPlace::orderBy('nama_perusahaan')->get();

        return response()->json($pklPlaces, 200);
    }


    public function getShifts($pklPlaceId)
    {
        // Fetch shifts based on the selected PKL place
        $shifts = Shift::where('pkl_place_id', $pklPlaceId)->get();

        if ($shifts->isEmpty()) {
            return response()->json([
                'message' => 'No shifts found for the given PKL place ID.'
            ], 404); // 404 is the HTTP status code for Not Found
        }

        return response()->json($shifts, 200); // 200 is the HTTP status code for OK
    }
    public function getByGuruPembimbingId(Request $request, $guru_pembimbing_id)
    {
        // Retrieve the search query from the request
        $search = $request->query('search');

        // Get the current year
        $currentYear = date('Y');

        // Start with the base query
        $query = PklPlace::where('guru_pembimbing_id', $guru_pembimbing_id)
            ->whereYear('created_at', $currentYear);  // Add the filter for the current year

        // Apply search filter if provided
        if ($search) {
            $query->where('nama_perusahaan', 'LIKE', "%{$search}%");
        }

        // Get the results
        $pklPlaces = $query->get();

        if ($pklPlaces->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No PKL places found for the given search criteria'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pklPlaces
        ], 200);
    }
    public function getGuruPembimbingId($id)
    {
        // Fetch the PKL Place data by ID
        $pklPlace = PklPlace::find($id);

        // Check if the PKL Place exists
        if (!$pklPlace) {
            return response()->json(['message' => 'PKL Place not found'], 404);
        }

        // Return the guru_pembimbing_id
        return response()->json(['guru_pembimbing_id' => $pklPlace->guru_pembimbing_id]);
    }
}
