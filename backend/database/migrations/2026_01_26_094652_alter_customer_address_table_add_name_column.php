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
            $table->string('full_name')->after('id');
            $table->string('phone_number')->after('full_name');
            $table->renameColumn('address_type', 'label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_address', function (Blueprint $table) {
            $table->dropColumn('full_name');
            $table->dropColumn('phone_number');
            $table->renameColumn('label', 'address_type');
        });
    }
};
