<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user', 'pklPlace', 'guruPembimbing')->get();
        // dd($attendances);
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $users = User::all();
        return view('attendances.create', compact('users'));
    }

    public function store(Request $request)
    {
        $attendance = new Attendance();
        $attendance->user_id = $request->input('user_id');
        $attendance->check_in = $request->input('check_in');
        $attendance->check_out = $request->input('check_out');
        $attendance->date = $request->input('date');
        $attendance->check_in_latitude = $request->input('check_in_latitude');
        $attendance->check_in_longitude = $request->input('check_in_longitude');
        $attendance->check_out_latitude = $request->input('check_out_latitude');
        $attendance->check_out_longitude = $request->input('check_out_longitude');
        $attendance->save();

        return redirect()->route('attendances.index')->with('success', 'Attendance created successfully');
    }

    public function show($id)
    {
        $attendance = Attendance::with('user')->find($id);
        return view('attendances.show', compact('attendance'));
    }

    public function edit($id)
    {
        $attendance = Attendance::find($id);
        $users = User::all();
        return view('attendances.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        $attendance->user_id = $request->input('user_id');
        $attendance->check_in = $request->input('check_in');
        $attendance->check_out = $request->input('check_out');
        $attendance->date = $request->input('date');
        $attendance->check_in_latitude = $request->input('check_in_latitude');
        $attendance->check_in_longitude = $request->input('check_in_longitude');
        $attendance->check_out_latitude = $request->input('check_out_latitude');
        $attendance->check_out_longitude = $request->input('check_out_longitude');
        $attendance->save();

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully');
    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully');
    }
}