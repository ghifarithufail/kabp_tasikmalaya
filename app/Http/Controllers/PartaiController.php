<?php

namespace App\Http\Controllers;

use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PartaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partais = Partai::orderBy('created_at', 'desc')->where('deleted','0')->get();

        return view('layouts.partai.index',[
            'partais' => $partais,
            'menu' => 'data',
            'subMenu' => 'partai'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.partai.create',[
            // 'korcams' => $korcams,
            'menu' => 'data',
            'subMenu' => 'partai'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
        ],[
            'nama.required' => 'Nama harus diisi',
        ]);

        $partai = new Partai($validatedData);

        if($request->file('foto')){
            $partai->foto = $request->file('foto')->store('partais');
        }

        $partai->save();
        
        Cache::forget('partais');
        Cache::forget('tim');

        return redirect()->route('data/partai');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $partai = Partai::find($id);
        
        return view('layouts.partai.edit',[
            'partai' => $partai,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        if(empty($request->file('foto'))){
            $partai = Partai::find($id);

            $partai->update([
                'nama' => $request->nama,
            ]);

            return redirect()->route('data/partai');
        }
        else{
            $partai = Partai::find($id);
            Storage::delete($partai->foto);
            $partai->update([
                'nama' => $request->nama,
                'foto' => $request->file('foto')->store('partais'),
            ]);

        return redirect()->route('data/partai');
        }

        // return redirect()->route('data/partai');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $partai = Partai::find($id);
        
        $partai->deleted = "1";
        $partai->save();

        return redirect()->back();
    }
}
