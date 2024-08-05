<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'date',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'guru_pembimbing',
        'pkl_places',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pklPlace()
    {
        return $this->belongsTo(PklPlace::class, 'pkl_places');
    }
    public function guruPembimbing()
    {
        return $this->belongsTo(Teacher::class, 'guru_pembimbing');
    }
}
