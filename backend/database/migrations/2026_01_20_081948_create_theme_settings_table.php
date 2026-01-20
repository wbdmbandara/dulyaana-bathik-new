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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('background_color')->default('#ffffff');
            $table->string('default_color')->default('#212529');
            $table->string('heading_color')->default('#3e0925');
            $table->string('accent_color')->default('#8c0d4f');
            $table->string('surface_color')->default('#ffffff');
            $table->string('contrast_color')->default('#ffffff');
            $table->string('nav_color')->default('#212529');
            $table->string('nav_hover_color')->default('#8c0d4f');
            $table->string('nav_mobile_background_color')->default('#ffffff');
            $table->string('nav_dropdown_background_color')->default('#ffffff');
            $table->string('nav_dropdown_color')->default('#212529');
            $table->string('nav_dropdown_hover_color')->default('#8c0d4f');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
