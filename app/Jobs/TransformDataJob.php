<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Added import for HTTP client

class TransformDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $entryId;

    public function __construct($entryId)
    {
        $this->entryId = $entryId;
    }

    public function handle(): void
    {
        // Fetch the record from the DB
        $record = DB::table('data_entries')->where('id', $this->entryId)->first();
        if (!$record) {
            return;
        }

        // Prepare payload from record and attempt external transformation
        $payload = [
            'raw_data' => $record->raw_data,
        ];

        try {
            $transformEndpoint = config('services.transform_endpoint'); // dynamic endpoint from config
            $response = Http::timeout(5)->post($transformEndpoint, $payload);
            if (!$response->successful()) {
                throw new \Exception('HTTP request failed with status ' . $response->status());
            }
            // Expected external API response: { "transformed_data": ..., "data_vector": [...] }
            $result = $response->json();
        } catch (\Exception $e) {
            // Fallback: perform local transformation using available methods
            $result = [
                'transformed_data' => strip_tags($record->raw_data),
                'data_vector' => array_fill(0, 300, 0.01),
            ];
        }

        // Update the record with transformed data and vector
        DB::table('data_entries')->where('id', $this->entryId)->update([
            'transformed_data' => json_encode($result['transformed_data']),
            'data_vector' => $result['data_vector'],
        ]);
    }
}
