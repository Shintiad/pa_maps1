<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Penyakit;
use App\Models\Tahun;

class MetabaseKasusService extends MetabaseBaseService
{
    // Database configuration
    private const DATABASE_ID = 2;
    private const TABLE_ID = 9; 
    private const COLLECTION_ID = 4;
    
    // Field IDs
    private const FIELD_CASES = 73;
    private const FIELD_DISTRICT = 72;
    private const FIELD_YEAR = 78;
    private const FIELD_DISEASE = 77;

    public function createDiseaseQuestion($tahun, $penyakit)
    {
        $this->refreshSessionIfNeeded();

        $tahunValue = Tahun::find($tahun)->tahun ?? $tahun;
        $penyakitValue = Penyakit::find($penyakit)->nama_penyakit ?? $penyakit;

        $questionData = [
            'name' => "Jumlah Terjangkit Penyakit {$penyakitValue} Kab. Lamongan Tahun {$tahunValue}",
            'description' => "Pemetaan kasus {$penyakitValue} berdasarkan kecamatan tahun {$tahunValue}",
            'collection_id' => self::COLLECTION_ID,
            'dataset_query' => [
                'database' => self::DATABASE_ID,
                'type' => 'query',
                'query' => [
                    'source-table' => self::TABLE_ID,
                    'aggregation' => [
                        ['sum', ['field', self::FIELD_CASES, 
                            ['base-type' => 'type/Integer']]
                        ]
                    ],
                    'breakout' => [
                        ['field', self::FIELD_DISTRICT, 
                            ['base-type' => 'type/BigInteger']
                        ]
                    ],
                    'filter' => [
                        'and',
                        ['=', ['field', self::FIELD_YEAR, 
                            ['base-type' => 'type/BigInteger']
                        ], $tahun],
                        ['=', ['field', self::FIELD_DISEASE, 
                            ['base-type' => 'type/Text']
                        ], $penyakit]
                    ]
                ]
            ]
        ];

        $response = Http::timeout(30)
            ->withHeaders([
                'X-Metabase-Session' => $this->sessionId
            ])
            ->post("{$this->baseUrl}/api/card", 
                array_merge($questionData, $this->getDefaultMapSettings())
            );

        if (!$response->successful()) {
            Log::error('Failed to create disease case map', [
                'tahun' => $tahun,
                'penyakit' => $penyakit,
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            throw new \Exception('Failed to create disease case map: ' . $response->body());
        }

        return $response->json();
    }

    public function getEmbedUrl($cardId)
    {
        try {
            if (empty($this->sessionId)) {
                throw new \Exception('No valid session ID available');
            }

            Log::info('Getting embed URL for card', ['cardId' => $cardId]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-Metabase-Session' => $this->sessionId
                ])
                ->post($this->baseUrl . "/api/card/{$cardId}/public_link");

            if (!$response->successful()) {
                Log::error('Failed to get embed URL', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to get embed URL: ' . $response->body());
            }

            $uuid = $response->json('uuid');
            if (empty($uuid)) {
                throw new \Exception('UUID not found in response');
            }

            $embedUrl = "{$this->baseUrl}/public/question/{$uuid}";
            Log::info('Successfully got embed URL', ['metabase_url' => $embedUrl]);

            return $embedUrl;
        } catch (\Exception $e) {
            Log::error('Error getting embed URL: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
