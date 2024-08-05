@extends('layouts.home')

@section('title', 'Tambah Jadwal Absen')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Tambah Jadwal Absen</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Form Tambah Jadwal Absen</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" action="{{ route('absence-schedule.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="user_id">Pilih Siswa:</label>
                                <select name="user_id" id="user_id" class="form-control select2" required>
                                    <option value="">Pilih Siswa</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nisn }} - {{$user->nama}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pkl_place_id">Pilih Tempat PKL:</label>
                                <select name="pkl_place_id" id="pkl_place_id" class="form-control select2" required onchange="fetchShifts(this.value)">
                                    <option value="">Pilih Tempat PKL</option>
                                    @foreach($pklPlaces as $place)
                                        <option value="{{ $place->id }}">{{ $place->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="shift_id">Pilih Shift:</label>
                                <select name="shift_id" id="shift_id" class="form-control select2" required>
                                    <option value="">Pilih Shift</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" onclick="return validateForm()">Simpan</button>
                                <a href="{{ route('absence-schedule.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    function fetchShifts(pklPlaceId) {
        if (pklPlaceId) {
            fetch(`/get-shifts/${pklPlaceId}`)
                .then(response => response.json())
                .then(data => {
                    let shiftSelect = document.getElementById('shift_id');
                    shiftSelect.innerHTML = '<option value="">Pilih Shift</option>';
                    data.forEach(shift => {
                        let option = document.createElement('option');
                        option.value = shift.id;
                        option.textContent = `${shift.shift_name} (${shift.start_time} - ${shift.end_time})`;
                        shiftSelect.appendChild(option);
                    });
                });
        } else {
            document.getElementById('shift_id').innerHTML = '<option value="">Pilih Shift</option>';
        }
    }

    function validateForm() {
        var user_id = document.getElementById("user_id").value;
        var pkl_place_id = document.getElementById("pkl_place_id").value;
        var shift_id = document.getElementById("shift_id").value;
        if (user_id === '' || pkl_place_id === '' || shift_id === '') {
            alert("Harap pilih siswa, tempat PKL, dan shift terlebih dahulu.");
            return false;
        }
        return true;
    }
</script>

@if ($errors->any())
    <script>
        alert("{{ $errors->first() }}");
    </script>
@endif

@endsection
