<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function showPenyakit(Request $request) {
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        $query = Penyakit::query();

        if ($sort === 'nama_penyakit' && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy('nama_penyakit', $direction);
        }

        $penyakit = $query->paginate(10);

        return view("pages.penyakit", compact("penyakit", "sort", "direction"));
    }
    public function create() {
        return view("add.add-penyakit");
    }
    public function store(Request $request) {
        $request->validate([
            'nama_penyakit' => 'required|max:255'
        ]);

        Penyakit::create([
            'nama_penyakit' => $request->nama_penyakit
        ]);

        return redirect()->route('penyakit');
    }
    public function edit($id) {
        $penyakit = Penyakit::find($id);
        return view("edit.edit-penyakit", compact("penyakit"));
    }
    public function update(Request $request, $id) {
        $penyakit = Penyakit::find($id);
        $penyakit->update($request->all());
        return redirect()->route('penyakit');
    }
    public function destroy($id) {
        $penyakit = Penyakit::find($id);

        // Hapus data yang terkait
        $penyakit->namaPenyakit()->delete();
        
        // Hapus entitas utama
        $penyakit->delete();
        return redirect()->route('penyakit');
    }
}
