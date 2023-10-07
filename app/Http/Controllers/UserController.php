<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $token = Str::random(120);

        $jwt = 'ey' . $token;

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Password mu salah oi'], 401);
        }

        $role = $user->role;
        $id_user = $user->id_user;
        $nama = $user->nama;

        return response()->json(compact('jwt', 'role' , 'id_user', 'nama'));
    }
    
    public function index()
    {
        $dt_user = User::all();
        return response()->json($dt_user);
    }

    public function getkasir()
    {
        $kasir = User::where('role','kasir')->get();
            return response()->json($kasir);
    }
    
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->tojson(), 422);
        }

        $create = User::create([
            'nama' => $req->input('nama'),
            'email' => $req->input('email'),
            'gender' => $req->input('gender'),
            'password' => Hash::make($req->input('password')),
            'role' => $req->input('role'),
        ]);
        return response()->json([
            'Status' => 'Success'
        ]);
    }

    public function show($id)
    {
        $user = User::where('id_user', $id)->get();
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required',
            // 'password' => 'required',
            'gender' => 'required',
            'role' => 'required',
        ]);

        $user = User::where('id_user', $id)->update([
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'gender' => $request->input('gender'),
            'role' => $request->input('role'),
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::where('id_user', $id);
        $user->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
