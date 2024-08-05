<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $fillable = [
        'nip',
        'nama_guru',
        'password',
    ];

    public function pklPlaces()
    {
        return $this->hasMany(PklPlace::class, 'guru_pembimbing_id');
    }
}
