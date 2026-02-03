<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('whatsapp', 20)->nullable(); // Tambahkan field whatsapp
            $table->enum('role', ['Admin', 'Guru', 'Staf', 'Murid', 'Orang Tua', 'Alumni', 'Guest'])->default('Orang Tua');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Tidak Aktif');
            $table->string('foto_profile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // FIELD KEAMANAN TAMBAHAN
            $table->string('registration_ip', 45)->nullable(); // IP saat registrasi
            $table->string('last_login_ip', 45)->nullable(); // IP login terakhir
            $table->text('user_agent')->nullable(); // Browser info
            $table->timestamp('last_login_at')->nullable(); // Waktu login terakhir
            $table->unsignedTinyInteger('login_attempts')->default(0); // Percobaan login gagal
            $table->timestamp('locked_until')->nullable(); // Waktu akun dikunci
            $table->string('activation_token')->nullable(); // Token aktivasi email
            $table->timestamp('token_expires_at')->nullable(); // Expired token
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete untuk audit trail
            
            // Indexes untuk performa
            $table->index('email');
            $table->index('whatsapp');
            $table->index('status');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}