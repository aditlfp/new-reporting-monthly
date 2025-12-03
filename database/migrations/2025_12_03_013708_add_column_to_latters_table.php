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
        Schema::table('latters', function (Blueprint $table) {
            $table->string('letter_to');
            $table->string('lamp')->nullable()->default('Satu Bendel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('latters', function (Blueprint $table) {
            $table->dropColumn('letter_to');
            $table->dropColumn('lamp');
        });
    }
};
