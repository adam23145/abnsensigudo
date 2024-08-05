@extends('layouts.home')

@section('title', 'Daftar Shifts')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Shifts</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Shift</h3>
                        <a href="{{ route('shifts.create') }}" class="btn btn-primary pull-right">Tambah Shift</a>
                    </div>
                    <div class="box-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <table class="table table-bordered table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>Nama Shift</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Tempat PKL</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->shift_name }}</td>
                                    <td>{{ $shift->start_time }}</td>
                                    <td>{{ $shift->end_time }}</td>
                                    <td>{{ $shift->pklPlace->nama_perusahaan }}</td>
                                    <td>
                                        <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus shift ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default" style="margin-left: 10px;margin-bottom:10px" onclick="window.location='{{ route('pkl-places.index') }}'">Kembali</button>
                    <br>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection