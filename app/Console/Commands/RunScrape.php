<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prism:scrape {url : The URL to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scraping tasks for PrismSpring';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        try {
            $response = Http::get($url);
            if (!$response->successful()) {
                throw new \Exception('HTTP request failed with status ' . $response->status());
            }
            $html = $response->body();
            
            // Parse HTML to extract all h1 texts
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query('//h1');
            $data = [];
            foreach ($nodes as $node) {
                $data[] = trim($node->nodeValue);
            }

            // Capture the raw HTML and store in the database
            \DB::table('data_entries')->insert([
                'source' => $url,
                'raw_data' => $html,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info('Scraped Data:', $data);
            $this->info('Scraped Data: ' . json_encode($data));
        } catch (\Exception $e) {
            Log::error('Scraping error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
