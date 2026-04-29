<?php

use App\Models\UploadImage;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_image_ratings', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(UploadImage::class);
            $table->string('rating_value', 20);
            $table->text('rating_reason')->nullable();
            $table->foreignIdFor(User::class, 'rated_by_user_id');
            $table->timestamp('rated_at')->nullable();
            $table->timestamps();

            $table->unique('upload_image_id', 'upload_image_ratings_unique_upload');
            $table->index('rated_by_user_id', 'upload_image_ratings_rated_by_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_image_ratings');
    }
};

