<?php

namespace OurCore\Core\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Unit extends Model
{
    use HasTranslations, HasUlids;

    protected $table = 'core_units';

    protected $guarded = [];

    public array $translatable = ['name'];
}
