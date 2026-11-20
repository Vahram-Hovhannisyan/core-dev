<?php

namespace OurCore\Core\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasUlids;

    protected $table = 'core_brands';

    protected $guarded = [];
}
