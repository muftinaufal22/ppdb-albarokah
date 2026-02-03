<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataOrangTua extends Model
{
    use HasFactory;

    protected $table = 'data_orang_tuas';
    protected $fillable = ['user_id'];
}
