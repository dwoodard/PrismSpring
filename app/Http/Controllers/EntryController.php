<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntryRequest;
use App\Http\Requests\UpdateEntryRequest;
use App\Models\Entry;
use Illuminate\Contracts\Pipeline\Pipeline;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = Entry::all();
        return response()->json($entries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntryRequest $request)
    {
        $data = $request->validated([
            'source' => 'required|url',
            'type' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|integer',
            'raw_data' => 'nullable|string',
            'transformed_data' => 'nullable|string',
            'embeddings' => 'required|array|size:1536',
            'metadata' => 'nullable|array',
            'status' => 'nullable|string',
            'processed_at' => 'nullable|date',
        ]);
        
        app(Pipeline::class)
            ->send($data)
            ->through([
                // pipe entry to markdown
                \App\Pipelines\EntryToMarkdown::class,
                // pipe entry to get embeddings
                \App\Pipelines\EntryToEmbeddings::class,
                // pipe entry to get metadata
                \App\Pipelines\EntryToMetadata::class,
                // pipe entry to get title
                \App\Pipelines\EntryToTitle::class,
            ])
            ->thenReturn();
        
        $entry = Entry::create($data);



        return response()->json($entry);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        return response()->json($entry);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntryRequest $request, Entry $entry)
    {
        $data = $request->validate([
            'source'           => 'sometimes|required|string',
            'type'             => 'nullable|string',
            'title'            => 'nullable|string',
            'description'      => 'nullable|string',
            'file_path'        => 'nullable|string',
            'file_size'        => 'nullable|integer',
            'raw_data'         => 'nullable|string',
            'transformed_data' => 'nullable|string',
            'embeddings'       => 'sometimes|required|array|size:1536',
            'metadata'         => 'nullable|array',
            'status'           => 'nullable|string',
            'processed_at'     => 'nullable|date',
        ]);

        $entry->update($data);

        return response()->json($entry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        $entry->delete();
        return response()->json(null, 204);
    }
}
