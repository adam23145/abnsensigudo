<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklPlace extends Model
{
    protected $fillable = [
        'nama_perusahaan', 'nib_siup', 'alamat_pkl', 'guru_pembimbing_id',
        'lokasi_pkl_lat', 'lokasi_pkl_long', 'hari_kerja_efektif'
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function guruPembimbing()
    {
        return $this->belongsTo(Teacher::class, 'guru_pembimbing_id');
    }
}
