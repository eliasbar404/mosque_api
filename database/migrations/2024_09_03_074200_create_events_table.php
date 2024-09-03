<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title'); // Article title
            $table->string('slug')->unique()->nullable(); // SEO-friendly URL slug
            $table->longText('description'); // Article content, use mediumText or longText if needed

            $table->string('image')->nullable(); 
            $table->enum('status',['draft','published'])->default('draft');

            $table->integer('view_count')->default(0); // View count
            $table->timestamp('published_at')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
