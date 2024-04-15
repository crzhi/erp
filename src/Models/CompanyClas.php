<?php

namespace Uupt\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 企业分类
 */
class CompanyClas extends Model
{
    use SoftDeletes;

    protected $table = 'company_class';

}
