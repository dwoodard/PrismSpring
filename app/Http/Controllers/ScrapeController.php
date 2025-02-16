<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ScrapeController extends Controller
{
    // Endpoint to trigger a scraping task
    public function triggerScrape(Request $request)
    {
        Artisan::call('prism:scrape');
        return response()->json(['message' => 'Scrape task triggered.']);
    }

    // Endpoint to check the status (placeholder logic)
    public function checkStatus(Request $request)
    {
        return response()->json(['status' => 'running']);
    }

    // Endpoint to retrieve processed data
    public function getData(Request $request)
    {
        $data = DB::table('data_entries')->get();
        return response()->json($data);
    }
}
