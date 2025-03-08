<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Role;
use App\Models\User;
use App\Models\Partai;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $name = $request->input('name');
        $partai = $request->input('partai');
        $partais = Partai::orderBy('nama', 'asc')->get();

        $users = User::with('listKelurahan')
            ->where('deleted', '!=', 1)
            ->where('name', '!=', 'admin');


        if ($request['name']) {
            $users = $users->where('name', 'like', '%' . $request['name'] . '%')
                ->orWhere('username', 'like', '%' . $request['name'] . '%');
        }

        if ($request['partai']) {
            $users = $users->where('partai', $request['partai'] );
        }

        $user = $users->paginate(20)->appends($request->all());


        return view('layouts.users.index', [
            'menu' => 'users',
            'subMenu' => 'user',
            'user' => $user,
            'partais' => $partais,
            'request' => [
                'name' => $name,
                'partai' => $partai,
            ],
        ]);
    }

    public function create()
    {
        return view('layouts.users.create', [
            'menu' => 'users',
            'subMenu' => 'user',
            'listRole' => Role::whereNull('deleted_at')->get(),
            'partais' => Partai::where('deleted', '=', '0')->get(),
            'kelurahans' => Kelurahan::where('deleted', '=', '0')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'partai' => 'required',
            'email' => 'nullable',
            'status' => 'required',
            'role' => 'required',
            'kelurahan_id' => 'required'
        ], [
            'username.unique' => 'username sudah terdaftar'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'partai' => $request->partai,
            'kelurahan_id' => $request->kelurahan_id,
            'email' => $request->email,
            'status' => $request->status,
            'role' => $request->role,
        ]);

        $role = Role::find($request->role);
        $user->assignRole($role->name);

        return redirect()->route('user')->with('success', 'Data User Berhasil Dibuat!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('layouts.users.edit', [
            'menu' => 'users',
            'subMenu' => 'user',
            'data' => $user,
            'roles' => Role::whereNull('deleted_at')->get(),
            'partais' => Partai::all(),
            'kelurahans' => Kelurahan::where('deleted', '=', '0')->where('id', '!=', $user->kelurahan_id)->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // Validasi input sesuai kebutuhan

        \Log::info($request->all());


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'partai' => 'required',
            'username' => 'required',
            'status' => 'required',
            'role' => 'required',
            'kelurahan_id' => 'required',
        ]);


        $role = Role::find($validatedData['role']);
        $user->syncRoles([$role->name]);

        \Log::info($validatedData);
        // Perbarui data pengguna yang tidak termasuk kata sandi
        $user->update($validatedData);
        // Periksa apakah kata sandi diisi atau tidak
        if (!empty($request->password)) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
            // $validatedData['password'] = bcrypt($request->password);
        }



        return redirect()->route('user')->with('success', 'Data User Berhasil Diupdate!');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->deleted = 1;
        $user->status = 1;
        $user->save();

        return redirect()->route('user')->with('success', 'Data User Berhasil Dihapus!');
    }
}
