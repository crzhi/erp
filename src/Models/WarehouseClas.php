<?php

namespace ManoCode\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 仓库管理
 */
class WarehouseClas extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_class';

    protected $fillable = ['label', 'parent_id'];

    public function children()
    {
        return $this->hasMany(WarehouseClas::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(WarehouseClas::class, 'parent_id');
    }

    public function recursiveChildren()
    {
        return $this->children()->with('recursiveChildren');
    }
}
