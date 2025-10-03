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
        Schema::create('footer_contents', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->text('about_text')->nullable();
            $table->string('play_store_link')->nullable();
            $table->string('app_store_link')->nullable();
            $table->string('address')->nullable();
            $table->string('email01')->nullable();
            $table->string('email02')->nullable();
            $table->string('phone01')->nullable();
            $table->string('phone02')->nullable();
            $table->string('open_time')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_contents');
    }
};
