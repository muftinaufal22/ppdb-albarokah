<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SppSetting extends Model
{
    use HasFactory;

    protected $table = 'spp_setting';
    
    protected $fillable = [
        'amount',
        'kelas_id',
        'tahun_ajaran', 
        'bulan',
        'update_by'
    ];

    protected $casts = [
        'amount' => 'integer',
        'kelas_id' => 'integer',
        'update_by' => 'integer'
    ];

    /**
     * Relasi dengan tabel kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi dengan user yang melakukan update
     */
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    /**
     * Scope untuk filter berdasarkan kelas
     */
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Scope untuk filter berdasarkan tahun ajaran
     */
    public function scopeByTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }
}