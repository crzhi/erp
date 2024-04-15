<?php

namespace Uupt\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 企业管理
 */
class Company extends Model
{
    use SoftDeletes;

    protected $table = 'company';
    
}
