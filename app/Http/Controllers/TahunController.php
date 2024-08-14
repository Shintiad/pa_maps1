<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function showTahun() {
        $tahun = Tahun::all();
        return view("pages.tahun", compact("tahun"));
    }
    public function create() {
        return view("add.add-tahun");
    }
    public function store(Request $request) {
        $request->validate([
            'tahun' => 'required|max:255'
        ]);

        Tahun::create([
            'tahun' => $request->tahun
        ]);

        return redirect()->route('tahun');
    }
    public function edit($id) {
        $tahun = Tahun::find($id);
        return view("edit.edit-tahun", compact("tahun"));
    }
    public function update(Request $request, $id) {
        $tahun = Tahun::find($id);
        $tahun->update($request->all());
        return redirect()->route('tahun');
    }
    public function destroy($id) {
        $tahun = Tahun::findOrFail($id);
        
        // Hapus data yang terkait
        $tahun->tahunPenduduk()->delete();
        $tahun->tahunPenyakit()->delete();
        
        // Hapus entitas utama
        $tahun->delete();
        
        return redirect()->route('tahun');
    }
}
