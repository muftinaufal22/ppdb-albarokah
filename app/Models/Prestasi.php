<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Prestasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'kategori',
        'tingkat',
        'peraih',
        'penyelenggara',
        'tanggal_prestasi',
        'gambar',
        'status',
        'views',
    ];

    protected $casts = [
        'tanggal_prestasi' => 'date',
        'views'            => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /** 🔎 Scope: hanya ambil yang status aktif */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /** 🔎 Scope: filter by tahun */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_prestasi', $year);
    }

    /** 🔎 Scope: pencarian keyword */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%")
              ->orWhere('kategori', 'like', "%{$keyword}%")
              ->orWhere('tingkat', 'like', "%{$keyword}%");
        });
    }

    /** 🖼️ Accessor: otomatis kembalikan URL gambar */
    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : asset('images/no-image.png');
    }

    /** 🔧 Boot method dikosongkan karena slug ditangani Controller */
    protected static function boot()
    {
        parent::boot();
        // Logika slug dihapus dari sini
    }
}