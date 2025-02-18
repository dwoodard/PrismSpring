<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\HTMLToMarkdown\HtmlConverter;
use League\HTMLToMarkdown\Converter\TableConverter;
use League\HTMLToMarkdown\Converter\ImageConverter;
use App\Services\DataEntryRepository; // added import
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Providers\Ollama;


class RunScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mindex:scrape {url : The URL to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scraping tasks for a given URL';

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
            
            // // Parse HTML to extract all h1 texts
            // libxml_use_internal_errors(true);
            // $dom = new \DOMDocument();
            // $dom->loadHTML($html);
            // $xpath = new \DOMXPath($dom);
            // $nodes = $xpath->query('//h1');
            // $data = [];
            // foreach ($nodes as $node) {
            //     $data[] = trim($node->nodeValue);
            // }



            $converter = new HtmlConverter([
                'strip_tags' => true,
                'hard_break' => true,
                'preserve_comments' => true,
                'strip_placeholder_links' => true,
                'use_autolinks' => true,
                'remove_nodes' => 'script',
            ]);

            


            $converter->getEnvironment()
            ->addConverter(new TableConverter())
            ;
            $markdown = $converter->convert($html);

            try {
                $embedding = Prism::embeddings()
                    ->using(Provider::Ollama, 'nomic-embed-text')
                    ->fromInput($markdown)
                    ->generate();
            } catch (PrismException $e) {
                Log::error('Embeddings generation failed:', [
                    'error' => $e->getMessage()
                ]);
            }

            $repository = new DataEntryRepository();
            $id = $repository->store([
                'source' => $url,
                'transformed_data' => $markdown,
                'embedding' => json_encode($embedding->embeddings)
            ]);

            $this->info('Scraped Data stored with ID: ' . $id);
        } catch (\Exception $e) {
            Log::error('Scraping error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
