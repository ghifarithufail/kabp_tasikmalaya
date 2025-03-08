<?php

namespace App\Http\Controllers;

use App\Models\Calon;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CalonController extends Controller
{
    public function index()
    {
        return view('layouts.calon.index', [
            'calon' => Calon::all(),
            'partai' => Partai::all()
        ]);
    }

    public function create()
    {
        return view('layouts.calon.create', [
            'partais' => Partai::where('deleted', '=', '0')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'partai' => 'nullable|array', // Pastikan 'partai' berupa array
            'status' => 'required',
            'gambar' => 'nullable|image',
            'kategori' => 'required',
            'daerah_pemilihan' => 'required'
        ], [
            'name.required' => 'nama harus diisi',
            'status.required' => 'status harus diisi',
        ]);

        if ($request->partai) {
            // Ubah array 'partai' menjadi string dengan menggunakan implode
            $validatedData['partai'] = implode(',', $validatedData['partai']);
        }

        $calon = new Calon($validatedData);
        if ($request->hasFile('gambar')) {
            $calon->gambar = $request->file('gambar')->store('calon');
        }
        $calon->save();

        return redirect()->route('data/calon')->with('success', 'Calon Walkot Berhasil dibuat');
    }


    public function edit($id)
    {
        $calon = Calon::find($id);

        // Memecah string partai menjadi array
        $calonPartaiArray = explode(',', $calon->partai);

        return view('layouts.calon.edit', [
            'data' => $calon,
            'selectedPartais' => $calonPartaiArray, // Mengirim array partai terpilih
            'partais' => Partai::where('deleted', '=', '0')->get()
        ]);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'partai' => 'nullable|array', // Pastikan 'partai' berupa array
            'status' => 'required',
            'gambar' => 'nullable|image',
            'kategori' => 'required',
            'daerah_pemilihan' => 'required'
        ], [
            'name.required' => 'nama harus diisi',
            'status.required' => 'status harus diisi',
        ]);

        if ($request->partai) {
            $validatedData['partai'] = implode(',', $validatedData['partai']);
        } else {
            $validatedData['partai'] = null;
        }

        if (empty($request->file('gambar'))) {
            $partai = Calon::find($id);

            $partai->update([
                'name' => $validatedData['name'],
                'partai' => $validatedData['partai'],
                'status' => $validatedData['status'],
                'kategori' => $validatedData['kategori'],
                'daerah_pemilihan' => $validatedData['daerah_pemilihan']
            ]);

            return redirect()->route('data/calon')->with('success', 'Calon Walkot Berhasil Diupdate');
        } else {
            $partai = Calon::find($id);

            $filePath = public_path('uploads/calon/' . $partai->gambar);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            // Storage::delete($partai->gambar);
            $partai->update([
                'name' => $validatedData['name'],
                'partai' => $validatedData['partai'],
                'status' => $validatedData['status'],
                'gambar' => $request->file('gambar')->store('calon'),
                'kategori' => $validatedData['kategori'],
                'daerah_pemilihan' => $validatedData['daerah_pemilihan']
            ]);

            return redirect()->route('data/calon')->with('success', 'Calon Walkot Berhasil Diupdate');
        }
    }

    public function destroy($id)
    {
        $user = Calon::find($id);
        $filePath = public_path('uploads/calon/' . $user->gambar);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $user->delete();

        return redirect()->route('data/calon')->with('success', 'Data Calon Berhasil Dihapus!');
    }
}
