<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'whatsapp',
        'role',
        'status',
        'foto_profile',
        'password',
        'kelas_id',
        'registration_ip',
        'last_login_ip',
        'user_agent',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    protected $dates = [
        'deleted_at',
        'last_login_at',
        'locked_until',
    ];

    /**
     * Cek apakah user aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'Aktif';
    }

    /**
     * Cek apakah akun terkunci
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    // Relasi yang sudah ada (jangan dihapus)
    public function userDetail()
    {
        return $this->hasOne(UsersDetail::class, 'user_id', 'id');
    }

    public function muridDetail()
    {
        return $this->hasOne(dataMurid::class, 'user_id', 'id');
    }

    public function dataOrtu()
    {
        return $this->hasOne(DataOrangTua::class, 'user_id', 'id');
    }

    public function berkas()
    {
        return $this->hasOne(BerkasMurid::class, 'user_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(PaymentSpp::class, 'user_id', 'id');
    }

    public function bank()
    {
        return $this->hasOne(BankAccount::class, 'user_id', 'id');
    }

    public function banks()
    {
        return $this->hasMany(BankAccount::class, 'user_id', 'id');
    }

    public function setting()
    {
        return $this->hasOne(Setting::class, 'user_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPaymentSpp::class, 'payment_id', 'id');
    }

    public function sppSettings()
    {
        return $this->hasMany(SppSetting::class, 'kelas_id', 'kelas_id');
    }

    public function getCurrentSppSetting($bulan = null, $tahunAjaran = '2024/2025')
    {
        $query = SppSetting::where('kelas_id', $this->kelas_id)
            ->where('tahun_ajaran', $tahunAjaran);
        
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        
        return $query->first();
    }

    public function detailPayments()
    {
        return $this->hasMany(DetailPaymentSpp::class, 'user_id', 'id');
    }

    public function detailPaymentsForYear($tahunAjaran = '2024/2025')
    {
        return $this->detailPayments()
            ->whereHas('payment', function($query) use ($tahunAjaran) {
                $query->where('year', $tahunAjaran);
            });
    }

    public function hasPaidSppForMonth($bulan, $tahunAjaran = '2024/2025')
    {
        return $this->detailPayments()
            ->where('month', $bulan)
            ->where('status', 'paid')
            ->whereHas('payment', function($query) use ($tahunAjaran) {
                $query->where('year', $tahunAjaran);
            })
            ->exists();
    }

    public function getSppAmount($bulan, $tahunAjaran = '2024/2025')
    {
        $sppSetting = $this->getCurrentSppSetting($bulan, $tahunAjaran);
        return $sppSetting ? $sppSetting->amount : 0;
    }
}