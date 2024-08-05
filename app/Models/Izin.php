<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;
    
    // Define the table associated with the model
    protected $table = 'izins';
    
    // Tambahkan 'guru_pembimbing_id' dan 'pkl_place_id' ke dalam $fillable
    protected $fillable = [
        'user_id',
        'date',
        'description',
        'photo_path',
        'guru_pembimbing_id',
        'pkl_place_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Tambahkan relasi untuk guru_pembimbing
    public function guruPembimbing()
    {
        return $this->belongsTo(User::class, 'guru_pembimbing_id');
    }
    
    // Tambahkan relasi untuk pkl_place
    public function pklPlace()
    {
        return $this->belongsTo(PklPlace::class, 'pkl_place_id');
    }
}
