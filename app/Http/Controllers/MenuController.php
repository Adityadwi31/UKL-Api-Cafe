<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function getmenu()
    {
        $dt_menu = Menu::all();
        return response()->json($dt_menu);
    }

    public function show($id)
    {
        $menu = menu::where('id_menu', $id)->get();
        return response()->json($menu);
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_menu' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
            'foto' => 'required|file',
            'harga' => 'required',
            // 'jumlah_pesan' => 'required', 
        ]);

        $foto = time() . '.' . $req->foto->extension();
        $req->foto->move(public_path('images'), $foto);

            $save=DB::table('menu')->insert([
                'nama_menu' => $req->get('nama_menu'),
                'jenis' => $req->get('jenis'),
                'deskripsi' => $req->get('deskripsi'),
                'foto' => $foto,
                'harga' => $req->get('harga'),
                'jumlah_pesan' => 0
            ]);


        return response()->json(['message' => 'Data berhasil ditambahkan']);
    }


    public function updatemenu(Request $r, $id)
    {
        $update = Menu::where('id_menu', $id)->update([
            'nama_menu' => $r->input('nama_menu'),
            'jenis' => $r->input('jenis'),
            'deskripsi' => $r->input('deskripsi'),
            'harga' => $r->input('harga'),  
        ]);

        if ($update) {
            return response()->json(['Message' => 'Berhasil']);
        } else {
            return response()->json(['Message' => 'Gagal']);
        }
    }

    public function updatephoto(Request $req, $id)
    {
        $upfoto = time() . '.' . $req->foto->extension();
        $req->foto->move(public_path('images'), $upfoto);

        $update = Menu::where('id_menu', $id)->update([
            'foto' => $upfoto
        ]);

        return response()->json([
            "Message" => "Berhasil",
            "Result" => $update
        ]);
    }


    public function destroy($id)
    {
        $dt_meja = Menu::findOrFail($id);
        $dt_meja->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}

?>