<?php

namespace ManoCode\Erp\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ManoCode\Erp\Http\Controllers\GoodController;
use ManoCode\Erp\Http\Controllers\PurchaseController;
use ManoCode\Erp\Models\Purchase;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 采购订单
 *
 * @method Purchase getModel()
 * @method Purchase|\Illuminate\Database\Query\Builder query()
 */
class PurchaseService extends AdminService
{
    protected string $modelName = Purchase::class;

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
            $items[$key]['status_name'] = array_column(erp_admin_dict_options('purchase.status'),'label','value')[$item['status']];
            if(isset($item['detail']) && is_string($item['detail']) && strlen($item['detail'])>=1){
                $items[$key]['detail'] = json_decode($item['detail'],true);
            }
        }

        return compact('items', 'total');
    }

    /**
     * 详情 获取数据
     *
     * @param $id
     *
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getDetail($id)
    {
        $query = $this->query();

        $this->addRelations($query, 'detail');
        $detail = $query->find($id);
        $detail['status_name'] = array_column(erp_admin_dict_options('purchase.status'),'label','value')[$detail['status']];
        if(isset($detail['detail']) && is_string($detail['detail']) && strlen($detail['detail'])>=1){
            $detail['detail'] = json_decode($detail['detail'],true);
        }
        return $detail;
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
        if(!(isset($data['id']) && strval($data['id'])>=1)){
            $data['status'] = 5;// 如果是创建的话状态默认为 待提交
        }
        if(!(isset($data['order_no']) && is_string($data['order_no']) && strlen($data['order_no'])>=1)){
            $data['order_no'] = Str::random(38);
        }
        // 替换时间模板
        $data['coding'] = str_replace('{datetime}',date('Y-m-d H:i:s'),$data['coding']);
        // 替换 UUID
        $data['coding'] = str_replace('{uuid}',Str::uuid(),$data['coding']);
        // 采购商品详情
        if(isset($data['detail']) && is_array($data['detail']) && count($data['detail'])>=1){
            $data['detail'] = json_encode($data['detail']);
        }
    }
}
