@extends('layouts.home')

@section('title', 'Tambah Pengguna Baru')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Tambah Pengguna Baru</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tambah Pengguna Baru</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nisn">NISN:</label>
                                <input type="text" id="nisn" name="nisn" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="siswa">Siswa</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection