@extends('layouts.home')

@section('title', 'Daftar Pengguna')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Data User</h1>
    </section>
    <section class="content">
        <!-- Tampilkan notifikasi -->
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data User</h3>
                    </div>
                    <div class="box-body">
                        <!-- Form untuk upload file Excel -->
                        <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Import Data Excel:</label>
                                <input type="file" name="file" id="file" accept=".xlsx, .xls" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                            <a href="{{ route('export.users') }}" class="btn btn-success">Download Template Excel</a>
                        </form>
                    </div>
                    <div class="box-body">
                        <!-- Tabel untuk menampilkan data pengguna -->
                        <table id="users-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>No Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($users as $user)
                                    @if($user->role != "admin")
                                        <tr>
                                            <td>{{ $counter }}</td>
                                            <td>{{ $user->nisn }}</td>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->jurusan ? $user->jurusan->nama_jurusan : '-' }}</td>
                                            <td>{{ $user->kelas ? $user->kelas->nama_kelas : '-' }}</td>
                                            <td>{{ $user->no_telepon }}</td>
                                            <td>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php $counter++; @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="{{ route('users.create') }}" class="btn btn-success">Tambah Pengguna Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
