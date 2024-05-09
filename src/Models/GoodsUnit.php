<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 计量单位
 */
class GoodsUnit extends Model
{
    use SoftDeletes;

    protected $table = 'goods_unit';
    
}
