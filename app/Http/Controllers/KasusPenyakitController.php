<?php

namespace App\Http\Controllers;

use App\Models\KasusPenyakit;
use App\Models\Kecamatan;
use App\Models\Penyakit;
use App\Models\Tahun;
use Illuminate\Http\Request;

class KasusPenyakitController extends Controller
{
    public function showKasus(Request $request)
    {
        $query = KasusPenyakit::query();

        // Filter berdasarkan tahun jika parameter 'tahun_id' ada
        if ($request->has('tahun_id') && !empty($request->tahun_id)) {
            $query->where('tahun_id', $request->tahun_id);
        }

        // Filter berdasarkan kecamatan jika parameter 'kecamatan_id' ada
        if ($request->has('kecamatan_id') && !empty($request->kecamatan_id)) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Filter berdasarkan penyakit jika parameter 'penyakit_id' ada
        if ($request->has('penyakit_id') && !empty($request->penyakit_id)) {
            $query->where('penyakit_id', $request->penyakit_id);
        }

        // Sorting
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');

        if ($sort) {
            switch ($sort) {
                case 'tahun':
                    $query->join('tahuns', 'kasus_penyakits.tahun_id', '=', 'tahuns.id')
                        ->orderBy('tahuns.tahun', $direction);
                    break;
                case 'nama_kecamatan':
                    $query->join('kecamatans', 'kasus_penyakits.kecamatan_id', '=', 'kecamatans.id')
                        ->orderBy('kecamatans.nama_kecamatan', $direction);
                    break;
                case 'nama_penyakit':
                    $query->join('penyakits', 'kasus_penyakits.penyakit_id', '=', 'penyakits.id')
                        ->orderBy('penyakits.nama_penyakit', $direction);
                    break;
                case 'terjangkit':
                    $query->orderBy('terjangkit', $direction);
                    break;
                default:
                    $query->orderBy($sort, $direction);
            }
        } else {
            // Default sorting
            $query->join('tahuns', 'kasus_penyakits.tahun_id', '=', 'tahuns.id')
                ->orderBy('tahuns.tahun', 'asc');
        }

        $kasus = $query->select('kasus_penyakits.*')->paginate(10);

        $tahun = Tahun::all();
        $kecamatan = Kecamatan::orderBy('id')->get();
        $penyakit = Penyakit::orderBy('id')->get();

        return view("pages.kasus-penyakit", compact("kasus", "tahun", "kecamatan", "penyakit", "sort", "direction"));
    }
    public function create()
    {
        $tahun = Tahun::all();
        $kecamatan = Kecamatan::all();
        $penyakit = Penyakit::all();

        return view("add.add-kasus-penyakit", compact("tahun", "kecamatan", "penyakit"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tahun_id' => 'required|exists:tahuns,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'penyakit_id' => 'required|exists:penyakits,id',
            'terjangkit' => 'required|integer|min:0',
        ]);

        KasusPenyakit::create($request->all());

        return redirect()->route('kasus');
    }
    public function edit($id)
    {
        $tahun = Tahun::all();
        $kecamatan = Kecamatan::all();
        $penyakit = Penyakit::all();
        $kasus = KasusPenyakit::find($id);

        return view("edit.edit-kasus-penyakit", compact("tahun", "kecamatan", "penyakit", "kasus"));
    }
    public function update(Request $request, $id)
    {
        $kasus = KasusPenyakit::find($id);
        $request->validate([
            'tahun_id' => 'required|exists:tahuns,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'penyakit_id' => 'required|exists:penyakits,id',
            'terjangkit' => 'required|integer|min:0',
        ]);

        $kasus->update($request->all());

        return redirect()->route('kasus');
    }
    public function destroy($id)
    {
        $kasus = KasusPenyakit::find($id);
        $kasus->delete();

        return redirect()->route('kasus');
    }
}
