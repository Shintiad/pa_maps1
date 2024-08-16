<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function showKecamatan(Request $request) {
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        $query = Kecamatan::query();

        if ($sort === 'nama_kecamatan' && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy('nama_kecamatan', $direction);
        }

        $kecamatan = $query->paginate(10);

        return view("pages.kecamatan", compact("kecamatan", "sort", "direction"));
    }
    public function create() {
        return view("add.add-kecamatan");
    }
    public function store(Request $request) {
        $request->validate([
            'nama_kecamatan' => 'required|max:255'
        ]);

        Kecamatan::create([
            'nama_kecamatan' => $request->nama_kecamatan
        ]);

        return redirect()->route('kecamatan');
    }
    public function edit($id) {
        $kecamatan = Kecamatan::find($id);
        return view("edit.edit-kecamatan", compact("kecamatan"));
    }
    public function update(Request $request, $id) {
        $kecamatan = Kecamatan::find($id);
        $kecamatan->update($request->all());
        return redirect()->route('kecamatan');
    }
    public function destroy($id) {
        $kecamatan = Kecamatan::find($id);

        // Hapus data yang terkait
        $kecamatan->kecamatanPenduduk()->delete();
        $kecamatan->kecamatanPenyakit()->delete();
        
        // Hapus entitas utama
        $kecamatan->delete();
        return redirect()->route('kecamatan');
    }
}
