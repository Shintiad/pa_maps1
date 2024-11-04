<?php

namespace App\Http\Controllers;

use App\Models\KasusPenyakit;
use App\Models\Kecamatan;
use App\Models\MapsPenyakit;
use App\Models\Penduduk;
use App\Models\Penyakit;
use App\Models\Tahun;
use App\Services\MetabasePendudukService;
use App\Services\MetabaseKasusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MapsController extends Controller
{
    private $metabasePendudukService;
    private $metabaseKasusService;

    public function __construct(MetabasePendudukService $metabasePendudukService, MetabaseKasusService $metabaseKasusService)
    {
        $this->metabasePendudukService = $metabasePendudukService;
        $this->metabaseKasusService = $metabaseKasusService;
    }

    public function showAllPenduduk()
    {
        $tahun = Tahun::with(['tahunPenduduk' => function ($query) {
            $query->select('tahun_id')
                ->groupBy('tahun_id');
        }])->get();

        $totalKecamatan = Kecamatan::count();

        $tahunData = $tahun->map(function ($item) use ($totalKecamatan) {
            $pendudukCount = Penduduk::where('tahun_id', $item->id)->count();

            return [
                'id' => $item->id,
                'tahun' => $item->tahun,
                'link_metabase' => $item->link_metabase,
                'data_status' => $pendudukCount === 0 ? 'no_data' : ($pendudukCount < $totalKecamatan ? 'incomplete' : 'complete'),
                'status_message' => $pendudukCount === 0 ? 'Belum ada data' : ($pendudukCount < $totalKecamatan ? 'Data belum lengkap' : '')
            ];
        });

        return view("pages.maps-penduduk", compact("tahunData", "totalKecamatan"));
    }

    public function showAllPenyakit()
    {
        $tahun = Tahun::all();
        $penyakit = Penyakit::all();

        return view("pages.maps-penyakit", compact("tahun", "penyakit"));
    }

    public function getMapLink(Request $request)
    {
        $tahunId = $request->input('tahun_id');
        $penyakitId = $request->input('penyakit_id');

        $totalKecamatan = Kecamatan::count();

        $kasusPenyakitCount = KasusPenyakit::where('tahun_id', $tahunId)
            ->where('penyakit_id', $penyakitId)
            ->count();

        $mapLink = MapsPenyakit::where('tahun_id', $tahunId)
            ->where('penyakit_id', $penyakitId)
            ->first();

        $response = [
            'link_metabase' => $mapLink->link_metabase ?? null,
            'status' => 'no_map',  
            'data_availability' => [] 
        ];

        if ($kasusPenyakitCount === 0) {
            $response['status'] = 'no_data';
        } elseif ($kasusPenyakitCount < $totalKecamatan) {
            $response['status'] = 'incomplete_data';
        } elseif ($mapLink && $mapLink->link_metabase) {
            $response['status'] = 'has_map';
        }

        $allDataAvailability = KasusPenyakit::selectRaw('tahun_id, penyakit_id, COUNT(*) as count')
            ->groupBy('tahun_id', 'penyakit_id')
            ->get();

        foreach ($allDataAvailability as $data) {
            $key = $data->tahun_id . '-' . $data->penyakit_id;
            $response['data_availability'][$key] = [
                'has_data' => $data->count > 0,
                'is_complete' => $data->count >= $totalKecamatan
            ];
        }

        return response()->json($response);
    }

    public function regenerateMapForYear($tahunId)
    {
        try {
            $tahun = Tahun::findOrFail($tahunId);

            $question = $this->metabasePendudukService->createQuestion($tahunId);

            if (isset($question['id'])) {
                $embedUrl = $this->metabasePendudukService->getEmbedUrl($question['id']);

                if ($embedUrl) {
                    $tahun->update([
                        'link_metabase' => $embedUrl
                    ]);
                    
                    // return response()->json([
                    //     'success' => true,
                    //     'embed_url' => $embedUrl,
                    //     'message' => "Map for year {$tahun->tahun} regenerated successfully"
                    // ]);
                    return redirect()->back()->with('success', "Peta untuk tahun {$tahun->tahun} berhasil dibuat");
                }
            }

            // return response()->json([
            //     'success' => false,
            //     'message' => 'Failed to regenerate map'
            // ], 500);
            return redirect()->back()->with('error', 'Gagal membuat peta');
        } catch (\Exception $e) {
            Log::error("Error regenerating map: " . $e->getMessage());

            // return response()->json([
            //     'success' => false,
            //     'message' => 'Failed to regenerate map: ' . $e->getMessage()
            // ], 500);
            return redirect()->back()->with('error', 'Gagal membuat peta: ' . $e->getMessage());
        }
    }

    public function regenerateMapForDisease($tahunId, $penyakitId)
    {
        try {
            $tahun = Tahun::find($tahunId);
            if (!$tahun) {
                // return response()->json([
                //     'success' => false,
                //     'message' => "Data tahun {$tahun->tahun} tidak ditemukan"
                // ], 404);
                return redirect()->back()->with('error', "Data tahun {$tahun->tahun} tidak ditemukan");
            }

            $penyakit = Penyakit::find($penyakitId);
            if (!$penyakit) {
                // return response()->json([
                //     'success' => false,
                //     'message' => "Data penyakit {$penyakit->nama_penyakit} tidak ditemukan"
                // ], 404);
                return redirect()->back()->with('error', "Data penyakit {$penyakit->nama_penyakit} tidak ditemukan");
            }

            $kasusExists = KasusPenyakit::where('tahun_id', $tahunId)
                ->where('penyakit_id', $penyakitId)
                ->exists();

            if (!$kasusExists) {
                // return response()->json([
                //     'success' => false,
                //     'message' => "Data kasus tidak ditemukan untuk penyakit {$penyakit->nama_penyakit} tahun {$tahun->tahun}"
                // ], 404);
                return redirect()->back()->with('error', "Data kasus tidak ditemukan untuk penyakit {$penyakit->nama_penyakit} pada tahun {$tahun->tahun}");
            }

            $expectedKecamatanCount = Kecamatan::count();

            $actualKasusCount = KasusPenyakit::where('tahun_id', $tahunId)
                ->where('penyakit_id', $penyakitId)
                ->count();

            if ($actualKasusCount !== $expectedKecamatanCount) {
                $message = sprintf(
                    "Data kasus tidak lengkap untuk tahun %s dan penyakit %s. " .
                        "Dibutuhkan data untuk %d kecamatan, tetapi hanya ditemukan %d data.",
                    $tahun->tahun,
                    $penyakit->nama_penyakit,
                    $expectedKecamatanCount,
                    $actualKasusCount
                );

                Log::warning('Incomplete disease case data', [
                    'tahun_id' => $tahunId,
                    'penyakit_id' => $penyakitId,
                    'expected' => $expectedKecamatanCount,
                    'actual' => $actualKasusCount
                ]);

                // return response()->json([
                //     'success' => false,
                //     'message' => $message
                // ], 422);
                return redirect()->back()->with('error', $message);
            }

            DB::beginTransaction();

            try {
                $existingMap = MapsPenyakit::where('tahun_id', $tahunId)
                    ->where('penyakit_id', $penyakitId)
                    ->first();

                $question = $this->metabaseKasusService->createDiseaseQuestion($tahunId, $penyakitId);

                if (!isset($question['id'])) {
                    throw new \Exception('Gagal membuat question di Metabase');
                }

                $embedUrl = $this->metabasePendudukService->getEmbedUrl($question['id']);

                if (empty($embedUrl)) {
                    throw new \Exception('Gagal generate embed URL');
                }

                if ($existingMap) {
                    $existingMap->update([
                        'link_metabase' => $embedUrl,
                        'updated_at' => now()
                    ]);

                    $message = sprintf(
                        "Berhasil membuat peta untuk penyakit %s tahun %s",
                        $penyakit->nama_penyakit,
                        $tahun->tahun
                    );
                } else {
                    MapsPenyakit::create([
                        'tahun_id' => $tahunId,
                        'penyakit_id' => $penyakitId,
                        'link_metabase' => $embedUrl,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $message = sprintf(
                        "Berhasil membuat peta untuk penyakit %s tahun %s",
                        $penyakit->nama_penyakit,
                        $tahun->tahun
                    );
                }

                DB::commit();

                // return response()->json([
                //     'success' => true,
                //     'message' => $message,
                //     'embed_url' => $embedUrl,
                //     'action' => $existingMap ? 'updated' : 'created'
                // ]);
                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("Error dalam generate peta penyakit", [
                'tahun_id' => $tahunId,
                'penyakit_id' => $penyakitId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // return response()->json([
            //     'success' => false,
            //     'message' => 'Gagal generate peta: ' . $e->getMessage()
            // ], 500);
            return redirect()->back()->with('error', 'Gagal membuat peta: ' . $e->getMessage());
        }
    }
}
