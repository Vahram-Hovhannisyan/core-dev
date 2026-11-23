<?php

namespace OurCore\Core\Modules\Catalog\Services;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;
use OurCore\Core\Modules\Catalog\Models\Product;
use OurCore\Core\Modules\Catalog\Models\Unit;

class UnitConverter
{
    /** Количество в заданной единице -> в базовую единицу товара. */
    public function toBase(Product $product, Unit $unit, string $quantity): string
    {
        return (string) BigDecimal::of($quantity)
            ->multipliedBy($this->factor($product, $unit))
            ->toScale(6, RoundingMode::HalfUp);
    }

    /** Количество в базовой единице -> в заданную единицу. */
    public function fromBase(Product $product, Unit $unit, string $baseQuantity): string
    {
        return (string) BigDecimal::of($baseQuantity)
            ->dividedBy($this->factor($product, $unit), 6, RoundingMode::HalfUp);
    }

    /** Сколько целых упаковок нужно, чтобы покрыть количество (округление вверх). */
    public function wholeUnitsFor(Product $product, Unit $unit, string $baseQuantity): string
    {
        return (string) BigDecimal::of($baseQuantity)
            ->dividedBy($this->factor($product, $unit), 0, RoundingMode::Up);
    }

    private function factor(Product $product, Unit $unit): string
    {
        if ($unit->id === $product->base_unit_id) {
            return '1';
        }

        $row = $product->productUnits()->where('unit_id', $unit->id)->first();

        if ($row === null) {
            throw new InvalidArgumentException(
                "Unit {$unit->code} is not configured for product {$product->slug}."
            );
        }

        return (string) $row->factor;
    }
}
