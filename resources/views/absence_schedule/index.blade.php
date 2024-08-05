@extends('layouts.home')

@section('title', 'Daftar Jadwal Absen')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Jadwal Absen</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Jadwal Absen</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Shift</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absenceSchedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->user->nisn }} - {{$schedule->user->nama}}</td>
                                    <td>{{ $schedule->pklPlace->nama_perusahaan }}</td>
                                    <td>{{ $schedule->shift->shift_name }}</td>
                                    <td>{{ $schedule->shift->start_time }}</td>
                                    <td>{{ $schedule->shift->end_time }}</td>
                                    <td>
                                        <form method="post" action="{{ route('absence-schedule.destroy', $schedule->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                        <!-- <a href="{{ route('absence-schedule.edit', $schedule->id) }}" class="btn btn-primary">Edit</a> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <a href="{{ route('absence-schedule.create') }}" class="btn btn-success">Tambah Jadwal Absen Baru</a>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
