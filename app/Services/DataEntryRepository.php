<?php
// File: app/Services/DataEntryRepository.php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class DataEntryRepository
{ 

    // Define a unique prefix for data entries to avoid key collisions
    protected $keyPrefix = 'data_entries:';

    // set db2 as the default database for data entries
    public function __construct()
    {
        Redis::connection('data_entries')->select(2);
    }

    /**
     * Get the next auto-incrementing ID for data entries.
     *
     * @return int
     */
    public function getNextId(): int
    {
        return Redis::incr($this->keyPrefix . 'next_id');
    }

    /**
     * Store a new data entry in Redis.
     *
     * @param  array  $data
     * @return int  The ID of the stored entry.
     */
    public function store(array $data): int
    {
        $id = $this->getNextId();

        // Ensure timestamps are added
        $data['created_at'] = now()->toDateTimeString();
        $data['updated_at'] = now()->toDateTimeString();
        
        // Store the entry as a Redis hash using a clear key structure
        Redis::hmset($this->keyPrefix . $id, $data);

        return $id;
    }

    /**
     * Retrieve a data entry by its ID.
     *
     * @param  int  $id
     * @return array
     */
    public function get(int $id): array
    {
        return Redis::hgetall($this->keyPrefix . $id);
    }

    /**
     * Delete a data entry by its ID.
     *
     * @param  int  $id
     * @return int  Number of keys deleted.
     */
    public function delete(int $id): int
    {
        return Redis::del($this->keyPrefix . $id);
    }
}
