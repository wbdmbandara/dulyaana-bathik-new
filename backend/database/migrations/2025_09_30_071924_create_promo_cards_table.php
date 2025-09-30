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
        Schema::create('promo_cards', function (Blueprint $table) {
            $table->id();
            $table->string('small_text',255);
            $table->string('promo_title',255);
            $table->text('promo_description');
            $table->string('promo_link',255);
            $table->string('button_text',255);
            $table->string('promo_image',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_cards');
    }
};
