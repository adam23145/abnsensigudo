@extends('layouts.home')

@section('title', 'Daftar Kehadiran')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Kehadiran</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Kehadiran</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NISN</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Date</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Status</th>
                                    <!-- <th>Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->user->nisn }}</td>
                                    <td>{{ $attendance->check_in }}</td>
                                    <td>{{ $attendance->check_out }}</td>
                                    <td>{{ $attendance->date }}</td>
                                    <td>{{ $attendance->pklPlace->nama_perusahaan }}</td>
                                    <td>{{ $attendance->guruPembimbing->nama_guru }}</td>
                                    <td>
                                        @if($attendance->status == 0)
                                        masuk
                                        @elseif($attendance->status == 1)
                                        izin
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <!-- <td>
                                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary">Edit</a>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection