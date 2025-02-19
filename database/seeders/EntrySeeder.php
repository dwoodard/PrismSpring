<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('entries')->insert([
            'source'            => 'example source',
            'type'              => 'pdf', // e.g., pdf, mp3, mp4, website, text
            'title'             => 'Example Title',
            'description'       => 'This is an example description.',
            'file_path'         => '/files/example.pdf',
            'file_size'         => 123456,
            'raw_data'          => 'Example raw data.',
            'transformed_data'  => 'Example transformed data.',
            'embeddings'        => json_encode(array_fill(0, 1536, 0)), // adjust as needed for your embeddings format
            'metadata'          => json_encode(['key' => 'value']),
            'status'            => 'pending',
            'processed_at'      => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
