<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use OurCore\Core\Modules\Catalog\Models\Product;
use OurCore\Core\Modules\Catalog\Models\Unit;

uses(RefreshDatabase::class);

it('attaches an image to a product and creates a thumbnail', function () {
    Storage::fake('public');

    $unit = Unit::create(['code' => 'pcs', 'type' => 'count', 'name' => ['en' => 'Piece']]);
    $product = Product::create([
        'slug' => 'chair',
        'name' => ['en' => 'Chair'],
        'base_unit_id' => $unit->id,
    ]);

    $product
        ->addMedia(UploadedFile::fake()->image('chair.jpg', 800, 600))
        ->toMediaCollection('images');

    expect($product->getMedia('images'))->toHaveCount(1);
    expect($product->getFirstMedia('images')->hasGeneratedConversion('thumb'))->toBeTrue();
});
