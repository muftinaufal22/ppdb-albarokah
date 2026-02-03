<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SppSetting;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    
    protected $fillable = [
        'nama'
    ];

    /**
     * Relasi dengan SPP Settings
     */
    public function sppSettings()
    {
        return $this->hasMany(SppSetting::class, 'kelas_id');
    }

    /**
     * Relasi dengan Users (murid-murid di kelas ini)
     */
    public function murid()
    {
        return $this->hasMany(User::class, 'kelas_id')->where('role', 'murid');
    }

    /**
     * Relasi dengan semua users yang terkait dengan kelas ini
     */
    public function users()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    /**
     * Scope untuk mencari kelas berdasarkan nama
     */
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama', 'like', '%' . $nama . '%');
    }
}