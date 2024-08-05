<?php
// app/Http/Controllers/TeacherController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use PHPUnit\TextUI\XmlConfiguration\Logging\TeamCity;
use Maatwebsite\Excel\Facades\Excel; // Import class Excel

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar guru.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Menampilkan form tambah guru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Menyimpan guru baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:teachers,nip',
            'nama_guru' => 'required',
            'password' => 'required|min:8|confirmed', // Tambahkan validasi password
        ]);

        $teacher = new Teacher();
        $teacher->nip = $request->nip;
        $teacher->nama_guru = $request->nama_guru;
        $teacher->password = bcrypt($request->password); // Hash password

        $teacher->save();

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }


    /**
     * Menampilkan form edit guru.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Menyimpan perubahan pada guru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|unique:teachers,nip,' . $id,
            'nama_guru' => 'required',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());

        return redirect()->route('teachers.index')->with('success', 'Perubahan pada guru berhasil disimpan.');
    }

    /**
     * Menghapus guru.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray([], $path)[0]; // Menggunakan Excel::toArray()

        if (!empty($data)) {
            $errors = [];
            foreach ($data as $key => $value) {
                // Abaikan baris pertama (header)
                if ($key > 0) {
                    // Cek apakah jumlah kolom sesuai dengan yang diharapkan
                    if (count($value) < 2) {
                        $errors[] = "Baris " . ($key + 1) . " tidak memiliki cukup kolom.";
                        continue;
                    }

                    // Cek apakah NIP dan Nama Guru tidak kosong
                    if (empty($value[0]) || empty($value[1])) {
                        $errors[] = "Baris " . ($key + 1) . " memiliki data yang kosong.";
                        continue;
                    }

                    // Cek apakah NIP sudah ada di database
                    if (Teacher::where('nip', $value[0])->exists()) {
                        $errors[] = "Baris " . ($key + 1) . " memiliki NIP yang sudah ada: " . $value[0];
                        continue;
                    }

                    // Simpan data guru
                    $teachers = new Teacher([
                        'nip' => $value[0],
                        'nama_guru' => $value[1],
                        'password' => bcrypt('smkgudo$231'),
                    ]);
                    $teachers->save();
                }
            }

            // Jika ada error, kembalikan dengan pesan error
            if (!empty($errors)) {
                return redirect()->back()->withErrors($errors);
            }
        }

        return redirect()->back()->with('success', 'Data imported successfully.');
    }

    public function exportTemplate()
    {
        // Path ke template Excel yang sudah disiapkan (misalnya di dalam folder storage/app)
        $templatePath = storage_path('app/templates/teachers_template.xlsx');

        // Mendownload file template dengan nama yang sesuai
        return response()->download($templatePath, 'teacher_template.xlsx');
    }
}
