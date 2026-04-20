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
        Schema::create('upload_tambahan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_tambahan_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('file_size');
            $table->text('keterangan');
            $table->timestamps();

            $table->index('upload_tambahan_id', 'upload_tambahan_items_upload_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_tambahan_items');
    }
};

