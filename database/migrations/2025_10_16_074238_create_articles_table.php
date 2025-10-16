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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source'); 
            $table->string('source_id')->nullable(); 
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url');
            $table->string('url_to_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('category')->nullable();
            $table->string('language', 8)->nullable();
            $table->json('raw')->nullable(); 
            $table->timestamps();
            $table->softDeletes();

            $table->index(['source', 'published_at']);
            $table->index(['category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
