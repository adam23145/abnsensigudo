@extends('layouts.home')

@section('title', 'Daftar Tempat PKL')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Tempat PKL</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Tempat PKL</h3>
                    </div>
                    <div class="box-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <table class="table table-bordered" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>NIB/SIUP</th>
                                    <th>Alamat PKL</th>
                                    <th>Guru Pembimbing</th>
                                    <th>Lokasi PKL</th>
                                    <th>Hari Kerja Efektif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pklPlaces as $place)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $place->nama_perusahaan }}</td>
                                    <td>{{ $place->nib_siup }}</td>
                                    <td>{{ $place->alamat_pkl }}</td>
                                    <td>{{ $place->guruPembimbing->nama_guru }}</td>
                                    <td>{{ $place->lokasi_pkl_lat }}, {{ $place->lokasi_pkl_long }}</td>
                                    <td>{{ $place->hari_kerja_efektif }}</td>
                                    <td>
                                        <form action="{{ route('pkl-places.destroy', $place->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                        <form action="{{ route('shifts.index', ['pkl_place_id' => $place->id]) }}" method="GET">
                                            <button type="submit" class="btn btn-primary ml-2">Cek Shift</button>
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
        <a href="{{ route('pkl-places.create') }}" class="btn btn-success">Tambah Tempat PKL</a>
        <a href="{{ route('shifts.create') }}" class="btn btn-primary pull-right">Tambah Shift</a>
    </section>
</div>
@endsection