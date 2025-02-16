<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataEntriesForVectors extends Migration
{
    public function up()
    {
        Schema::table('data_entries', function (Blueprint $table) {
            // Add a new column "data_vector" to store the vector representation.
            // Adjust dimensions (e.g., 300) according to your LLM model's embedding size.
            $table->vector('data_vector', 300)->nullable();
        });
    }

    public function down()
    {
        Schema::table('data_entries', function (Blueprint $table) {
            $table->dropColumn('data_vector');
        });
    }
}
