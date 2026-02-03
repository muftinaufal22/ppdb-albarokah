<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasi';

    protected $fillable = ['foto'];

    /**
     * Accessor untuk mendapatkan URL lengkap foto.
     * Dapat dipanggil di view sebagai $model->foto_url
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        
        // Gambar fallback jika file tidak ditemukan (praktik yang baik)
        return asset('images/placeholder.png'); // Pastikan Anda punya gambar placeholder
    }
}