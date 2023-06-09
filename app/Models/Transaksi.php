<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    public $timestamps = null;
    protected $table = "transaksi";
    protected $primaryKey = "id_transaksi";
    protected $fillable = ['id_keranjang', 'id_user', 'id_meja', 'id_menu', 'tanggal_pesan', 'nama_pelanggan', 'status', 'total_pesanan', 'total_harga'];
}
