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
            $table->jsonb('tags')->default('[]');
            $table->jsonb('platforms')->default('[]');
            $table->jsonb('tweet')->nullable();
            $table->timestamp('tweeted_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
};
