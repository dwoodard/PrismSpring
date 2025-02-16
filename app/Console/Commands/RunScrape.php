<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $client = new Client();
        $url = $this->argument('url');
        try {
            $crawler = $client->request('GET', $url);
            // Example: scrape all H1 text
            $data = $crawler->filter('h1')->each(function ($node) {
                return $node->text();
            });
            Log::info('Scraped Data:', $data);
            $this->info('Scraped Data: ' . json_encode($data));
        } catch (\Exception $e) {
            Log::error('Scraping error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
