@extends('layouts.home')

@section('title', 'Daftar Kelas')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Kelas</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Kelas</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelases as $kelas)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kelas->nama_kelas }}</td>
                                    <td>
                                        <form action="{{ route('kelases.destroy', $kelas->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <a href="{{ route('kelases.edit', $kelas->id) }}" class="btn btn-primary">Edit</a> -->
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
        <a href="{{ route('kelases.create') }}" class="btn btn-success">Tambah Kelas Baru</a>
    </section>
</div>
@endsection
