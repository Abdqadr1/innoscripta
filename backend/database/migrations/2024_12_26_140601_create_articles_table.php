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


            $table->string('title');
            $table->mediumText('content');

            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->text('url') ;

            $table->foreignId('author_id')->nullable()->constrained('authors', 'id');
            $table->foreignId('source_id')->nullable()->constrained('news_sources', 'id');

            $table->dateTime('published_at', 6)->nullable();
            $table->timestamps(6);
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
