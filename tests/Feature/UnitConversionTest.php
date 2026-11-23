<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use OurCore\Core\Modules\Catalog\Models\Product;
use OurCore\Core\Modules\Catalog\Models\ProductUnit;
use OurCore\Core\Modules\Catalog\Models\Unit;
use OurCore\Core\Modules\Catalog\Services\UnitConverter;

uses(RefreshDatabase::class);

function tileProduct(): array
{
    $m2 = Unit::create(['code' => 'm2', 'type' => 'area', 'name' => ['en' => 'Square meter']]);
    $pack = Unit::create(['code' => 'pack', 'type' => 'count', 'name' => ['en' => 'Pack']]);

    $product = Product::create([
        'slug' => 'tile-60x60',
        'name' => ['en' => 'Tile 60x60'],
        'base_unit_id' => $m2->id,
    ]);

    ProductUnit::create([
        'product_id' => $product->id,
        'unit_id' => $pack->id,
        'factor' => '2.4',
    ]);

    return [$product, $m2, $pack];
}

it('converts packs to base units', function () {
    [$product, , $pack] = tileProduct();

    expect(app(UnitConverter::class)->toBase($product, $pack, '5'))->toBe('12.000000');
});

it('converts base units to packs', function () {
    [$product, , $pack] = tileProduct();

    expect(app(UnitConverter::class)->fromBase($product, $pack, '12'))->toBe('5.000000');
});

it('rounds up to whole packs', function () {
    [$product, , $pack] = tileProduct();

    // 10 м² / 2,4 = 4,17 -> нужно 5 упаковок
    expect(app(UnitConverter::class)->wholeUnitsFor($product, $pack, '10'))->toBe('5');
});

it('treats the base unit as factor 1', function () {
    [$product, $m2] = tileProduct();

    expect(app(UnitConverter::class)->toBase($product, $m2, '3'))->toBe('3.000000');
});

it('falls back to another language', function () {
    [$product] = tileProduct();

    app()->setLocale('hy');

    expect($product->name)->toBe('Tile 60x60');
});
