<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'type',
        'title',
        'description',
        'file_path',
        'file_size',
        'raw_data',
        'transformed_data',
        'embeddings',
        'metadata',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'metadata'     => 'array',
        'processed_at' => 'datetime',
        // If you want to work with embeddings as an array
        'embeddings'   => 'array',
    ];


    // relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }



    // usage: $entry->embeddings
    public function getEmbeddingsAttribute($value)
    {
        return json_decode($value, true);
    }

    
}
