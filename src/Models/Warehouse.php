<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 仓库管理
 */
class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse';
    
}
