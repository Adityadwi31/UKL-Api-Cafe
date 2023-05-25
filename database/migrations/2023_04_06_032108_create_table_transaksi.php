<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->timestamp('tgl_transaksi');
            // $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            // $table->foreign('id_meja')->references('id_meja')->on('meja')->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->enum('status', ['belum_bayar','lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_transaksi');
    }
};
