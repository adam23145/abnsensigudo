@extends('layouts.home')

@section('title', 'Daftar Jurusan')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Jurusan</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Jurusan</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jurusan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jurusans as $jurusan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jurusan->nama_jurusan }}</td>
                                    <td>
                                        <form action="{{ route('jurusans.destroy', $jurusan->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <a href="{{ route('jurusans.edit', $jurusan->id) }}" class="btn btn-primary">Edit</a> -->
                                            <button type="submit" class="btn btn-danger">Delete</button>
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
        <a href="{{ route('jurusans.create') }}" class="btn btn-success">Tambah Jurusan Baru</a>
    </section>
</div>
@endsection
