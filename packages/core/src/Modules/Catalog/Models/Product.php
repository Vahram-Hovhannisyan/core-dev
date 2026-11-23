<?php

namespace OurCore\Core\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations, HasUlids, InteractsWithMedia, SoftDeletes;

    protected $table = 'core_products';

    protected $guarded = [];

    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return ['attributes' => 'array'];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'core_category_product');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('position');
    }

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('documents');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->performOnCollections('images');
    }
}
