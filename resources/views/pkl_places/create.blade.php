@extends('layouts.home')

@section('title', 'Tambah Tempat PKL Baru')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Tambah Tempat PKL Baru</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Form Tambah Tempat PKL</h3>
                    </div>
                    <div class="box-body">
                        <form method="POST" action="{{ route('pkl-places.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="nama_perusahaan">Nama Perusahaan</label>
                                <input id="nama_perusahaan" type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required autofocus>
                                @error('nama_perusahaan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nib_siup">NIB/SIUP (Opsional)</label>
                                <input id="nib_siup" type="text" class="form-control @error('nib_siup') is-invalid @enderror" name="nib_siup" value="{{ old('nib_siup') }}">
                                @error('nib_siup')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="alamat_pkl">Alamat PKL</label>
                                <textarea id="alamat_pkl" class="form-control @error('alamat_pkl') is-invalid @enderror" name="alamat_pkl" required>{{ old('alamat_pkl') }}</textarea>
                                @error('alamat_pkl')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="guru_pembimbing_id">Guru Pembimbing</label>
                                <select id="guru_pembimbing_id" class="form-control @error('guru_pembimbing_id') is-invalid @enderror select2" name="guru_pembimbing_id" required>
                                    <option value="">Pilih Guru Pembimbing</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('guru_pembimbing_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lokasi_pkl_lat">Latitude PKL</label>
                                <input id="lokasi_pkl_lat" type="text" class="form-control @error('lokasi_pkl_lat') is-invalid @enderror" name="lokasi_pkl_lat" value="{{ old('lokasi_pkl_lat') }}" required>
                                @error('lokasi_pkl_lat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lokasi_pkl_long">Longitude PKL</label>
                                <input id="lokasi_pkl_long" type="text" class="form-control @error('lokasi_pkl_long') is-invalid @enderror" name="lokasi_pkl_long" value="{{ old('lokasi_pkl_long') }}" required>
                                @error('lokasi_pkl_long')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="getLocation()">Dapatkan Lokasi Sekarang</button>
                            </div>

                            <div class="form-group">
                                <label for="hari_kerja_efektif">Hari Kerja Efektif</label>
                                <input id="hari_kerja_efektif" type="text" class="form-control @error('hari_kerja_efektif') is-invalid @enderror" name="hari_kerja_efektif" value="{{ old('hari_kerja_efektif') }}" required>
                                @error('hari_kerja_efektif')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pkl-places.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript untuk mendapatkan lokasi pengguna -->
<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        document.getElementById('lokasi_pkl_lat').value = position.coords.latitude;
        document.getElementById('lokasi_pkl_long').value = position.coords.longitude;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }
</script>
@endsection
