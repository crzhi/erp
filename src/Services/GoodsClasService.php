<?php

namespace ManoCode\Erp\Services;

use ManoCode\Erp\Models\GoodsClas;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 商品分类
 *
 * @method GoodsClas getModel()
 * @method GoodsClas|\Illuminate\Database\Query\Builder query()
 */
class GoodsClasService extends AdminService
{
    protected string $modelName = GoodsClas::class;

    public function getTree(): array
    {
        $list = $this->query()->orderBy('sort')->get()->toArray();

        return $this->array2tree($list);
    }
    function array2tree(array $list, int $parentId = 0)
    {
        $data = [];
        foreach ($list as $key => $item) {
            if(isset($item['parent_id']) && intval($item['parent_id'])>=1){
                $list[$key]['parent_name'] = GoodsClas::query()->where(['id'=>$item['parent_id']]);
            }
            if ($item['parent_id'] == $parentId) {
                $children = array2tree($list, (int)$item['id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }
    public function list(): array
    {
        return ['items' => $this->getTree()];
    }
}
