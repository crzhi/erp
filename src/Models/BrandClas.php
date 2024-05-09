<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 品牌分类
 */
class BrandClas extends Model
{
    use SoftDeletes;

    protected $table = 'brand_class';
    
}
