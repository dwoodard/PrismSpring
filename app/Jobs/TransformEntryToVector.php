<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransformEntryToVector implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $entryId;

    /**
     * Create a new job instance.
     *
     * @param int $entryId
     */
    public function __construct(int $entryId)
    {
        $this->entryId = $entryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve the record from data_entries using the provided ID
        $record = DB::table('data_entries')->where('id', $this->entryId)->first();

        if (!$record) {
            Log::error("Record with ID {$this->entryId} not found.");
            return;
        }

        // Step 1: Data Transformation
        // For example, extract a summary from raw_data by stripping HTML tags.
        $cleanText = strip_tags($record->raw_data);
        $summary = substr($cleanText, 0, 200); // Simple summary: first 200 characters
        $transformed = [
            'summary' => $summary,
            'processed_at' => now()->toDateTimeString(),
            // You can add more structured fields as needed.
        ];

        // Step 2: Generate Embedding Vector
        // Replace this dummy function with an actual call to an embedding API.
        $vector = $this->generateEmbedding($cleanText);

        // Update the record with transformed_data and vector_data (formerly data_vector)
        DB::table('data_entries')->where('id', $this->entryId)->update([
            'transformed_data' => json_encode($transformed),
            'vector_data' => $vector,
            'updated_at' => now(),
        ]);

        Log::info("Record {$this->entryId} updated with transformed data and vector embedding.");
    }

    /**
     * Dummy function to simulate generating an embedding vector.
     * In production, replace this with an API call to your embedding service.
     *
     * @param string $text
     * @return array
     */
    protected function generateEmbedding(string $text): array
    {
        // Example: Return a dummy vector with 300 dimensions.
        // Replace with a call to an actual LLM/embedding API.
        return array_fill(0, 300, 0.05); // Dummy values
    }
}
