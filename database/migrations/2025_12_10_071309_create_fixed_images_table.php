<?php

use App\Models\Clients;
use App\Models\UploadImage;
use App\Models\User;
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
        Schema::create('fixed_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UploadImage::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Clients::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_images');
    }
};
