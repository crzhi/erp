<?php

namespace Uupt\Erp\Services;

use Uupt\Erp\Models\BrandClas;
use Uupt\Erp\Models\GoodsBrand;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 商品品牌
 *
 * @method GoodsBrand getModel()
 * @method GoodsBrand|\Illuminate\Database\Query\Builder query()
 */
class GoodsBrandService extends AdminService
{
    protected string $modelName = GoodsBrand::class;

    /**
     * 列表 获取数据
     *
     * @return array
     */
    public function list()
    {
        $query = $this->listQuery();

        $list = $query->paginate(request()->input('perPage', 20));
        $items = $list->items();
        $total = $list->total();
        foreach ($items as $key => $item) {
            $items[$key]['class_name'] = BrandClas::query()->where('id',$item['class'])->value('name');
        }
        return compact('items', 'total');
    }
}
