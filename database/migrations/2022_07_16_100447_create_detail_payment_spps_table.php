<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSppSettingTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update tabel spp_setting untuk menambah kolom yang dibutuhkan
        Schema::table('spp_setting', function (Blueprint $table) {
            // Tambah kolom yang dibutuhkan oleh SettingController
            $table->unsignedBigInteger('kelas_id')->after('id');
            $table->string('tahun_ajaran', 20)->after('kelas_id');
            $table->string('bulan', 20)->nullable()->after('tahun_ajaran');
            $table->boolean('is_active')->default(1)->after('amount');
            
            // Tambah foreign key constraint
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            
            // Tambah index untuk performance
            $table->index(['kelas_id', 'tahun_ajaran']);
            $table->index(['kelas_id', 'tahun_ajaran', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spp_setting', function (Blueprint $table) {
            // Drop foreign key dan index
            $table->dropForeign(['kelas_id']);
            $table->dropIndex(['kelas_id', 'tahun_ajaran']);
            $table->dropIndex(['kelas_id', 'tahun_ajaran', 'bulan']);
            
            // Drop kolom yang ditambahkan
            $table->dropColumn(['kelas_id', 'tahun_ajaran', 'bulan', 'is_active']);
        });
    }
}

// File terpisah: CreateDetailPaymentSppsTable.php (Opsional)
class CreateDetailPaymentSppsTable extends Migration
{
    /**
     * Run the migrations.
     * Tabel ini opsional jika ingin menyimpan detail pembayaran terpisah
     */
    public function up()
    {
        Schema::create('detail_payment_spps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('month', 20); // Bulan dalam bahasa Indonesia
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->string('file')->nullable(); // Nama file bukti pembayaran
            $table->string('nama_pengirim')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->text('admin_note')->nullable(); // Catatan admin jika ditolak
            $table->timestamp('confirmed_at')->nullable(); // Waktu konfirmasi
            $table->unsignedBigInteger('confirmed_by')->nullable(); // Admin yang konfirmasi
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('payment_id')->references('id')->on('payment_spps')->onDelete('cascade');
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
            
            // Unique constraint
            $table->unique(['payment_id', 'month']);
            
            // Index
            $table->index(['payment_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('detail_payment_spps');
    }
}