<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 商品品牌
 */
class GoodsBrand extends Model
{
    use SoftDeletes;

    protected $table = 'goods_brand';
    
}
