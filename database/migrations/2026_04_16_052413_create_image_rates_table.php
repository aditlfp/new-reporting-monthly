<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UploadImage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UploadImage::class,'upload_image_id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->tinyInteger('rate');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_rates');
    }
};
