<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterSpp extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'nominal',
        'tahun_ajaran',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}