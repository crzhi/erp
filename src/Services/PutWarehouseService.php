<?php

namespace ManoCode\Erp\Services;

use ManoCode\Erp\Models\Good;
use ManoCode\Erp\Models\GoodsUnit;
use ManoCode\Erp\Models\PutWarehouse;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 入库表
 *
 * @method PutWarehouse getModel()
 * @method PutWarehouse|\Illuminate\Database\Query\Builder query()
 */
class PutWarehouseService extends AdminService
{
    protected string $modelName = PutWarehouse::class;
    public function list()
    {
        $query = $this->listQuery();

        $list  = $query->paginate(request()->input('perPage', 20));
        $items = $list->items();
        $total = $list->total();
        foreach ($items as $key=>$item){
            if($goods = Good::query()->where('coding',$item['goods'])->first()){
                foreach (json_decode($goods->getAttribute('sku'),true) as $sku){
                    if($sku['coding'] == $item['sku']){
                        $items[$key]['sku'] = GoodsUnit::query()->where('id',$sku['unit'])->value('name').' X '.$sku['base_num'];
                    }
                }
            }
        }
        return compact('items', 'total');
    }
}
