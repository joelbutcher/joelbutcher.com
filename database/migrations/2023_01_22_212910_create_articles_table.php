<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt');
            $table->text('content');
            $table->boolean('published')->default(false);
            $table->string('tweet_id')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamp('shared_at')->nullable();
            $table->timestamps();
        });
    }
};
