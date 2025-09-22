<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Migration file: add_dynamic_fields_to_beasiswa_table.php
    public function up()
{
    Schema::table('beasiswas', function (Blueprint $table) {
        $table->json('form_fields')->nullable();
        $table->json('documents')->nullable();
    });
}

public function down()
{
    Schema::table('beasiswas', function (Blueprint $table) {
        $table->dropColumn(['form_fields', 'documents']);
    });
}
    /**
     * Reverse the migrations.
     */
};  
