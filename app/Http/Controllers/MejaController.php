<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Meja;
use Illuminate\Support\Facades\DB;

class MejaController extends Controller
{
    public function getmeja()
    {
        $dt_meja= Meja::get();
        return response()->json($dt_meja);
    }
    public function mejatersedia()
    {
        $meja = Meja::where('status', '!=' ,'digunakan')->get();
            return response()->json($meja);
    } 
    
    public function show($id)
    {
        $meja = Meja::where('id_meja', $id)->get();
        return response()->json($meja);
    }


    public function createmeja(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nomor_meja' => 'required|unique:meja'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 422);
        }
        $meja = Meja::create([
            'nomor_meja' => $req->input('nomor_meja'),
            'status' => 'kosong'
        ]);

        return response()->json(['message' => 'Data berhasil ditambahkan']);
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nomor_meja' => 'required',
            // 'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }

        $update = Meja::where('id_meja', $id)->update([
            'nomor_meja' => $req->input('nomor_meja'),
            'status' => $req->input('status')
        ]);

        if ($update) {
            return response()->json('Sukses');
        } else {
            return response()->json('gagal');
        }
    }

    public function destroy($id)
    {
        $delete = DB::table('meja')->where('id_meja', $id)->delete();
        if ($delete) {
            return response()->json('Berhasil Hapus meja');
        } else {
            return response()->json('Meja sudah tidak ada/terhapus');
        }
    }
}
