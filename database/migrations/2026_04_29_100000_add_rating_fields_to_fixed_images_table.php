<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_images', function (Blueprint $table): void {
            $table->string('rating_value', 20)->nullable()->after('clients_id');
            $table->text('rating_reason')->nullable()->after('rating_value');
            $table->unsignedBigInteger('rated_by_user_id')->nullable()->after('rating_reason');
            $table->timestamp('rated_at')->nullable()->after('rated_by_user_id');

            $table->index('rated_by_user_id', 'fixed_images_rated_by_user_idx');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_images', function (Blueprint $table): void {
            $table->dropIndex('fixed_images_rated_by_user_idx');
            $table->dropColumn(['rating_value', 'rating_reason', 'rated_by_user_id', 'rated_at']);
        });
    }
};

