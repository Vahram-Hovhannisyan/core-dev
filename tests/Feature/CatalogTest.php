<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use OurCore\Core\Modules\Catalog\Models\Category;
use OurCore\Core\Modules\Catalog\Models\Product;
use OurCore\Core\Modules\Catalog\Models\ProductVariant;
use OurCore\Core\Modules\Catalog\Models\Unit;

uses(RefreshDatabase::class);

it('builds a category tree', function () {
    $root = Category::create([
        'slug' => 'engine',
        'name' => ['ru' => 'Двигатель', 'en' => 'Engine'],
    ]);
    $child = Category::create([
        'slug' => 'filters',
        'name' => ['en' => 'Filters'],
        'parent_id' => $root->id,
    ]);

    expect($root->children)->toHaveCount(1);
    expect($child->parent->is($root))->toBeTrue();
    expect($root->getTranslation('name', 'ru'))->toBe('Двигатель');
});

it('stores a product with a variant and attributes', function () {
    $unit = Unit::create(['code' => 'pcs', 'type' => 'count', 'name' => ['en' => 'Piece']]);

    $product = Product::create([
        'slug' => 'oil-filter',
        'name' => ['en' => 'Oil filter'],
        'base_unit_id' => $unit->id,
        'attributes' => ['thread' => 'M20'],
    ]);

    ProductVariant::create(['product_id' => $product->id, 'sku' => 'OF-001']);

    $fresh = Product::with('variants')->find($product->id);

    expect($fresh->variants)->toHaveCount(1);
    expect($fresh->attributes['thread'])->toBe('M20');
    expect($fresh->status)->toBe('draft');
});
