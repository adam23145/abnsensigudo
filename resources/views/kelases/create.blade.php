@extends('layouts.home')

@section('title', 'Tambah Kelas Baru')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Tambah Kelas Baru</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Form Tambah Kelas</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('kelases.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas</label>
                                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kelases.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
