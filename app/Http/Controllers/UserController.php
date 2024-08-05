<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel; // Import class Excel
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with(['jurusan', 'kelas'])->get();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $jurusans = Jurusan::all();
        $kelases = Kelas::all();
        return view('users.create', compact('jurusans', 'kelases'));
    }

    /**
     * Menyimpan pengguna baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nisn' => 'required|unique:users',
            'nama' => 'required',
            'jurusan_id' => 'required|exists:jurusan,id',
            'kelas_id' => 'required|exists:kelas,id',
            'no_telepon' => 'required',
            'password' => 'nullable|min:6',
        ]);

        // Jika password kosong, gunakan NISN sebagai password
        $password = $request->password ? $request->password : $request->nisn;

        // Simpan pengguna baru
        User::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jurusan_id' => $request->jurusan_id,
            'kelas_id' => $request->kelas_id,
            'no_telepon' => $request->no_telepon,
            'password' => bcrypt($password),
            'role' => 'siswa', // default role is 'siswa'
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }


    /**
     * Menampilkan form edit pengguna.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $jurusans = Jurusan::all();
        $kelases = Kelas::all();
        return view('users.edit', compact('user', 'jurusans', 'kelases'));
    }

    /**
     * Menyimpan perubahan pada pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function update(Request $request, $id)
    // {
    //     // Validasi data input
    //     $request->validate([
    //         'nisn' => 'required|unique:users,nisn,' . $id,
    //         'nama' => 'required',
    //         'jurusan_id' => 'nullable|exists:jurusan,id',
    //         'kelas_id' => 'nullable|exists:kelas,id',
    //         'no_telepon' => 'required',
    //         'password' => 'nullable|min:6',
    //     ]);

    //     // Simpan perubahan pada pengguna
    //     $user = User::findOrFail($id);
    //     $data = $request->all();

    //     // Jika password diisi, lakukan hashing ulang
    //     if ($request->filled('password')) {
    //         $data['password'] = bcrypt($request->password);
    //     } else {
    //         unset($data['password']);
    //     }

    //     $user->update($data);

    //     return redirect()->route('users.index')->with('success', 'Perubahan pada pengguna berhasil disimpan.');
    // }

    /**
     * Menghapus pengguna.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray([], $path)[0]; // Menggunakan Excel::toArray()

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($key > 0) {
                    // Cari ID jurusan berdasarkan nama
                    $jurusan = Jurusan::where('nama_jurusan', $value[2])->first();
                    if (!$jurusan) {
                        return redirect()->back()->with('error', 'Jurusan ' . $value[2] . ' tidak ditemukan.');
                    }
                    $jurusanId = $jurusan->id;

                    // Cari ID kelas berdasarkan nama
                    $kelas = Kelas::where('nama_kelas', $value[3])->first();
                    if (!$kelas) {
                        return redirect()->back()->with('error', 'Kelas ' . $value[3] . ' tidak ditemukan.');
                    }
                    $kelasId = $kelas->id;

                    // Periksa apakah NISN sudah ada
                    $existingUser = User::where('nisn', $value[0])->first();
                    if (!$existingUser) {
                        $user = new User([
                            'nisn' => $value[0],
                            'nama' => $value[1],
                            'jurusan_id' => $jurusanId,
                            'kelas_id' => $kelasId,
                            'no_telepon' => $value[4],
                            'password' => bcrypt($value[5]), // Pastikan password di-hash sesuai kebutuhan
                            'role' => 'siswa',
                        ]);
                        $user->save();
                    } else {
                        // Jika NISN sudah ada, simpan pesan error ke dalam session
                        return redirect()->back()->with('error', 'NISN ' . $value[0] . ' sudah ada atau telah diimport sebelumnya.');
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Data imported successfully.');
    }

    public function exportTemplate()
    {
        // Path ke template Excel yang sudah disiapkan (misalnya di dalam folder storage/app)
        $templatePath = storage_path('app/templates/user_template.xlsx');

        // Mendownload file template dengan nama yang sesuai
        return response()->download($templatePath, 'user_import_template.xlsx');
    }
}
