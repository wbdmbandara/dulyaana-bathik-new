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
        Schema::table('customer_address', function (Blueprint $table) {
            // Change label from enum to string to allow any value
            $table->string('label', 50)->default('home')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_address', function (Blueprint $table) {
            $table->enum('label', ['billing', 'shipping'])->default('billing')->change();
        });
    }
};
