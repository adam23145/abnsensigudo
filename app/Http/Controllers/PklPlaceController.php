<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\PklPlace;
use App\Models\Teacher;
use Illuminate\Http\Request;

class PklPlaceController extends Controller
{
    // Menampilkan daftar tempat PKL
    public function index()
    {
        $pklPlaces = PklPlace::with('guruPembimbing')->get();
        return view('pkl_places.index', compact('pklPlaces'));
    }

    // Menampilkan form untuk menambah tempat PKL
    public function create()
    {
        $teachers = Teacher::all();
        return view('pkl_places.create', compact('teachers'));
    }

    // Menyimpan tempat PKL yang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat_pkl' => 'required',
            'guru_pembimbing_id' => 'required|exists:teachers,id',
            'lokasi_pkl_lat' => 'required',
            'lokasi_pkl_long' => 'required',
            'hari_kerja_efektif' => 'required',
        ]);

        PklPlace::create($request->all());

        return redirect()->route('pkl-places.index')->with('success', 'Tempat PKL berhasil ditambahkan.');
    }
    public function destroy($id)
    {
        // Temukan tempat PKL berdasarkan ID
        $pklPlace = PklPlace::findOrFail($id);

        // Hapus izin-izin yang terkait dengan tempat PKL ini dari tabel izins
        $izins = Izin::where('pkl_place_id', $id)->get();
        foreach ($izins as $izin) {
            $izin->delete();
        }

        // Hapus tempat PKL setelah izin-izin dihapus
        $pklPlace->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('pkl-places.index')->with('success', 'Tempat PKL dan izin terkait berhasil dihapus.');
    }
}
