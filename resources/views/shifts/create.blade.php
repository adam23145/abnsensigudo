@extends('layouts.home')

@section('title', 'Tambah Shift Baru')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>Tambah Shift Baru</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Form Tambah Shiftsdsadsa</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('shifts.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="start_time">Waktu Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="end_time">Waktu Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pkl_place_id">Tempat PKL</label>
                                <select class="form-control select2" id="pkl_place_id" name="pkl_place_id">
                                    <option value="">Pilih Tempat PKL</option>
                                    @foreach($pklPlaces as $pklPlace)
                                        <option value="{{ $pklPlace->id }}" {{ old('pkl_place_id') == $pklPlace->id ? 'selected' : '' }}>{{ $pklPlace->nama_perusahaan }} ({{ $pklPlace->nib_siup }})</option>
                                    @endforeach
                                </select>
                                @error('pkl_place_id')
                                    <span class="text-danger">{{ $message }}</span>
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
@endsection
