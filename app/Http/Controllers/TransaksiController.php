<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Transaksi;
use App\Models\Meja;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class TransaksiController extends Controller
{
    // get
    public function gettransaksi()
    {
        $get = Transaksi::get();
        return response()->json($get);
    }

    public function selecttransaksi($id)
    {
        $get = Transaksi::where('id_transaksi', $id)->get();
        return response()->json($get);
    }

    public function gethistory()
    {
        $get = DB::table('histori')
            ->join('users', 'histori.id_user', '=', 'users.id_user')
            ->orderBy('id_histori', 'desc')->get();
        return response()->json($get);
    }

    public function selecthistori($code)
    {
        $get = DB::table('transaksi')
            ->where('id_keranjang', $code)
            ->join('users', 'transaksi.id_user', '=', 'users.id_user')
            ->join('menu', 'transaksi.id_menu', '=', 'menu.id_menu')
            ->get();
        return response()->json($get);
    }

    public function getdate($date)
    {
        $get = Transaksi::where('tanggal_pesan', $date)->sum('total_harga');
        return response()->json($get);
    }

    public function getmonth($month)
    {
        $get = Transaksi::whereMonth('tanggal_pesan', substr($month, 5, 2))->sum('total_harga');
        return response()->json($get);
    }

    // tambah & prosesnya
    public function tambahpesanan(Request $req)
    {
        $harga_menu = DB::table('menu')->where('id_menu', $req->input('id_menu'))->select('harga')->first();
        $harga_menu = $harga_menu->harga;

        $tgl_pesan = carbon::now();
        $total_pesanan = $req->input('total_pesanan');
        $total_harga = $harga_menu * $total_pesanan;

        $tambah = Transaksi::create([
            'id_menu' => $req->input('id_menu'),
            'tanggal_pesan' => $tgl_pesan,
            'total_pesanan' => $total_pesanan,
            'total_harga' => $total_harga,
            'status' => 'belum_bayar'
        ]);
        $get = DB::table('menu')->where('id_menu', $req->input('id_menu'))->select('jumlah_pesan')->get();
        $jumlah_pesan = $get->isEmpty() ? 0 : $get[0]->jumlah_pesan;
        $addjumlahpesan = $jumlah_pesan + $total_pesanan;

        $add = DB::table('menu')->where('id_menu', $req->input('id_menu'))->update([
            'jumlah_pesan' => $addjumlahpesan
        ]);

        if ($tambah) {
            return response()->json('Berhasil');
        } else {
            return response()->json('Gagal');
        }
    }

    public function checkout(Request $req)
    {
        $id_keranjang = Str::random(6);

        $checkout = Transaksi::where('id_keranjang', null)->update([
            'id_keranjang' => $id_keranjang,
            'id_user' => $req->input('id_user'),
            'id_meja' => $req->input('id_meja'),
            'nama_pelanggan' => $req->input('nama_pelanggan')
        ]);

        $history = DB::table('histori')->insert([
            'id_keranjang' => $id_keranjang,
            'tgl_transaksi' => Carbon::now(),
            'id_user' => $req->input('id_user'),
            'nama_pelanggan' => $req->input('nama_pelanggan')
        ]);

        $updatemeja = Meja::where('id_meja', $req->input('id_meja'))->update([
            'status' => 'digunakan'
        ]);

        if ($checkout && $updatemeja && $history) {
            return response()->json('Berhasil');
        } else {
            return response()->json('Gagal');
        }
    }
    
    public function donetransaksi($id)
    {
        $done = Transaksi::where('id_meja', $id)->where('status', 'belum_bayar')->update([
            'status' => 'lunas'
        ]);

        $meja = Meja::where('id_meja', $id)->update([
            'status' => 'kosong'
        ]);

        if ($done && $meja) {
            return response()->json([
                'Message' => 'Sukses'
            ]);
        } else {
            return response()->json([
                'Message' => 'gagal'
            ]);
        }
    }

    public function ongoing()
    {
        $get = Meja::where('status', 'digunakan')->get();
        return response()->json($get);
    }

    public function getongoingtransaksi($id)
    {
        $gettransaksi = Transaksi::where('id_meja', $id)
            ->where('status', 'belum_bayar')
            ->first();
        return response()->json($gettransaksi);
    }

    public function getcart()
    {
        $cart = Transaksi::where('id_meja', null)
            ->join('menu', 'transaksi.id_menu', '=', 'menu.id_menu')
            ->get();
        return response()->json($cart);
    }

    // pertotalan
    public function total($code)
    {
        $get = Transaksi::where('id_keranjang', $code)->sum('total_harga');
        return response()->json($get);
    }

    public function totalharga($id)
    {
        $gettotal = Transaksi::where('id_meja', $id)->where('status', 'belum_bayar')->sum('total_harga');
        return response()->json($gettotal);
    }

    public function destroy($id)
    {
        $dt = Transaksi::findOrFail($id);
        $dt->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
