@extends('layouts.home')

@section('title', 'Daftar Guru')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Guru</h1>
    </section>
    <section class="content">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Guru</h3>
                    </div>
                    <div class="box-body">
                        <!-- Form untuk upload file Excel -->
                        <form action="{{ route('import.teachers') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Import Data Excel:</label>
                                <input type="file" name="file" id="file" accept=".xlsx, .xls" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                            <a href="{{ route('export.teachers') }}" class="btn btn-success">Download Template Excel</a>
                        </form>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Guru</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $teacher->nip }}</td>
                                    <td>{{ $teacher->nama_guru }}</td>
                                    <td>
                                        <!-- <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary">Edit</a> -->
                                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-success">Tambah Guru Baru</a>
    </section>
</div>
@endsection