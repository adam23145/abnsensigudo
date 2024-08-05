<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class DataSiswaAbsenController extends Controller
{
    public function getByPendampingId(Request $request, $id_pkl_places)
    {
        // Retrieve search query and pagination parameters
        $search = $request->query('search'); // Retrieve search query
        $page = $request->query('page', 1); // Default to page 1
        $limit = $request->query('limit', 10); // Default to 10 items per page

        // Initialize the query
        $query = Attendance::with('user')
            ->where('pkl_places', $id_pkl_places)
            ->where('status', 0); // Filter where status is 0

        // Apply search filter if search query is provided
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        // Apply pagination
        $attendances = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        // Check if data is empty
        if ($attendances->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No attendance records found for the given id_pkl_places'
            ], 404);
        }

        // Return paginated results
        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ], 200);
    }
}
