<?php

namespace ManoCode\Erp\Services;

use ManoCode\Erp\Models\Warehouse;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 仓库管理
 *
 * @method Warehouse getModel()
 * @method Warehouse|\Illuminate\Database\Query\Builder query()
 */
class WarehouseService extends AdminService
{
    protected string $modelName = Warehouse::class;

    /**
     * 列表 获取数据
     *
     * @return array
     */
    public function list()
    {
        $query = $this->listQuery();

        $list  = $query->paginate(request()->input('perPage', 20));
        $items = $list->items();
        $total = $list->total();
        foreach ($items as $key=>$item){
            if(isset($item['position']) && is_string($item['position']) && strlen($item['position'])>=1){
                $items[$key]['position'] = json_decode($item['position']);
            }
        }

        return compact('items', 'total');
    }

    /**
     * saving 钩子 (执行于新增/修改前)
     *
     * 可以通过判断 $primaryKey 是否存在来判断是新增还是修改
     *
     * @param $data
     * @param $primaryKey
     *
     * @return void
     */
    public function saving(&$data, $primaryKey = '')
    {
        if(isset($data['position']) && is_array($data['position']) && count($data['position'])>=1){
            $data['position'] = json_encode($data['position']);
        }
    }
    public function getDetail($id)
    {
        $query = $this->query();

        $this->addRelations($query, 'detail');

        $data = $query->find($id);
        if(isset($data['position']) && is_string($data['position']) && strlen($data['position'])>=1){
            $data['position'] = json_decode($data['position'],true);
        }
        return $data;
    }
    public function getEditData($id)
    {
        $model = $this->getModel();

        $hidden = collect([$model->getCreatedAtColumn(), $model->getUpdatedAtColumn()])
            ->filter(fn($item) => $item !== null)
            ->toArray();

        $query = $this->query();

        $this->addRelations($query, 'edit');
        $data = $query->find($id)->makeHidden($hidden);
        if(isset($data['position']) && is_string($data['position']) && strlen($data['position'])>=1){
            $data['position'] = json_decode($data['position'],true);
        }
        return $data;
    }
}
