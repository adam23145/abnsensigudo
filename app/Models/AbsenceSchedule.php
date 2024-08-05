<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pkl_place_id','shift_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pklPlace()
    {
        return $this->belongsTo(PklPlace::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
