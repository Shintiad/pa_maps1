<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Penduduk;
use App\Models\Tahun;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function showPenduduk(Request $request)
    {
        $query = Penduduk::orderByKecamatan();

        // Filter berdasarkan tahun jika parameter 'tahun_id' ada
        if ($request->has('tahun_id') && !empty($request->tahun_id)) {
            $query->where('tahun_id', $request->tahun_id);
        }

        // Filter berdasarkan kecamatan jika parameter 'kecamatan_id' ada
        if ($request->has('kecamatan_id') && !empty($request->kecamatan_id)) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        $penduduk = $query->paginate(10);

        $tahun = Tahun::all();
        $kecamatan = Kecamatan::orderBy('id')->get();

        return view("pages.penduduk", compact("penduduk", "tahun", "kecamatan"));
    }
    public function create()
    {
        $tahun = Tahun::all();
        $kecamatan = Kecamatan::all();

        return view("add.add-penduduk", compact("tahun", "kecamatan"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tahun_id' => 'required|exists:tahuns,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'jumlah_penduduk' => 'required|integer|min:0',
        ]);

        Penduduk::create($request->all());

        return redirect()->route('penduduk');
    }
    public function edit($id)
    {
        $tahun = Tahun::all();
        $kecamatan = Kecamatan::all();
        $penduduk = Penduduk::find($id);

        return view("edit.edit-penduduk", compact("tahun", "kecamatan", "penduduk"));
    }
    public function update(Request $request, $id)
    {
        $penduduk = penduduk::find($id);
        $request->validate([
            'tahun_id' => 'required|exists:tahuns,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'jumlah_penduduk' => 'required|integer|min:0',
        ]);

        $penduduk->update($request->all());

        return redirect()->route('penduduk');
    }
    public function destroy($id)
    {
        $penduduk = Penduduk::find($id);
        $penduduk->delete();

        return redirect()->route('penduduk');
    }
}