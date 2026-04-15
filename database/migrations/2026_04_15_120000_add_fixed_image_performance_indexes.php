<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upload_images', function (Blueprint $table): void {
            $table->index(
                ['clients_id', 'status', 'created_at', 'user_id'],
                'upload_images_client_status_created_user_idx'
            );
        });

        Schema::table('fixed_images', function (Blueprint $table): void {
            $table->index(
                ['clients_id', 'created_at', 'upload_image_id'],
                'fixed_images_client_created_upload_idx'
            );

            $table->index(
                ['upload_image_id', 'created_at'],
                'fixed_images_upload_created_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('fixed_images', function (Blueprint $table): void {
            $table->dropIndex('fixed_images_client_created_upload_idx');
            $table->dropIndex('fixed_images_upload_created_idx');
        });

        Schema::table('upload_images', function (Blueprint $table): void {
            $table->dropIndex('upload_images_client_status_created_user_idx');
        });
    }
};
