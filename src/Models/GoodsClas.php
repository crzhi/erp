<?php

namespace Uupt\Erp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slowlyo\OwlAdmin\Models\BaseModel as Model;

/**
 * 商品分类
 */
class GoodsClas extends Model
{
    use SoftDeletes;

    protected $table = 'goods_class';

    protected $fillable = ['label', 'parent_id'];

    public function children()
    {
        return $this->hasMany(GoodsClas::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(GoodsClas::class, 'parent_id');
    }

    public function recursiveChildren()
    {
        return $this->children()->with('recursiveChildren');
    }
}
