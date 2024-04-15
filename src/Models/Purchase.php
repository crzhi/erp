<?php

namespace Uupt\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 采购订单
 */
class Purchase extends Model
{
    use SoftDeletes;

    protected $table = 'purchase';
    
}
