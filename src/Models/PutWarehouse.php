<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 入库表
 */
class PutWarehouse extends Model
{
    use SoftDeletes;

    protected $table = 'put_warehouse';
    
}
