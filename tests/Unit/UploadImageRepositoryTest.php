<?php

use Tests\TestCase;

use App\Models\UploadImage;
use App\Models\User;
use App\Repositories\EloquentUploadImageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

uses(TestCase::class);

beforeEach(function () {
    UploadImage::flushEventListeners();

    Schema::dropIfExists('upload_images');
    Schema::create('upload_images', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('clients_id');
        $table->string('img_before')->nullable();
        $table->string('img_proccess')->nullable();
        $table->string('img_final')->nullable();
        $table->string('note');
        $table->integer('max_data')->default(0);
        $table->integer('status')->default(0);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('upload_images');
});

it('counts user drafts for a month', function () {
    $repository = new EloquentUploadImageRepository();

    UploadImage::query()->create([
        'user_id' => 5,
        'clients_id' => 10,
        'img_before' => 'before.jpg',
        'img_proccess' => 'none',
        'img_final' => 'final.jpg',
        'note' => 'draft one',
        'max_data' => 14,
        'status' => 0,
        'created_at' => Carbon::parse('2026-04-10'),
        'updated_at' => Carbon::parse('2026-04-10'),
    ]);

    UploadImage::query()->create([
        'user_id' => 5,
        'clients_id' => 10,
        'img_before' => 'before-2.jpg',
        'img_proccess' => 'none',
        'img_final' => 'final-2.jpg',
        'note' => 'submitted',
        'max_data' => 14,
        'status' => 1,
        'created_at' => Carbon::parse('2026-04-11'),
        'updated_at' => Carbon::parse('2026-04-11'),
    ]);

    expect($repository->countUserDraftsForMonth(5, Carbon::parse('2026-04-15')))->toBe(1);
});

it('creates and updates upload images through repository', function () {
    $repository = new EloquentUploadImageRepository();

    $upload = $repository->createUpload([
        'user_id' => 7,
        'clients_id' => 14,
        'img_before' => 'before.jpg',
        'img_proccess' => 'none',
        'img_final' => 'final.jpg',
        'note' => 'initial',
        'max_data' => 14,
        'status' => 0,
    ]);

    $updated = $repository->updateUpload($upload, [
        'note' => 'updated',
        'status' => 1,
    ]);

    expect($updated->note)->toBe('updated')
        ->and($updated->status)->toBe(1);
});
