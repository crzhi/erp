<?php

namespace ManoCode\Erp\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ManoCode\Erp\Http\Controllers\GoodController;
use ManoCode\Erp\Models\Good;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 商品管理
 *
 * @method Good getModel()
 * @method Good|\Illuminate\Database\Query\Builder query()
 */
class GoodService extends AdminService
{
    protected string $modelName = Good::class;

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
            $items[$key]['status_name'] = array_column(erp_admin_dict_options('goods.status'),'label','value')[$item['status']];
            $items[$key]['pass_status_name'] = array_column(erp_admin_dict_options('goods.pass_status'),'label','value')[$item['pass_status']];
            if(isset($item['sku']) && is_string($item['sku']) && strlen($item['sku'])>=1){
                $items[$key]['sku'] = json_decode($item['sku'],true);
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
        $detail['status_name'] = array_column(erp_admin_dict_options('goods.status'),'label','value')[$detail['status']];
        $detail['pass_status_name'] = array_column(erp_admin_dict_options('goods.pass_status'),'label','value')[$detail['pass_status']];
        if(isset($detail['sku']) && is_string($detail['sku']) && strlen($detail['sku'])>=1){
            $detail['sku'] = json_decode($detail['sku'],true);
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
        // 替换时间模板
        $data['coding'] = str_replace('{datetime}',date('Y-m-d H:i:s'),$data['coding']);
        // 替换分类
        $data['coding'] = str_replace('{type}',$data['class'],$data['coding']);
        // 审核默认值
        if(!(isset($data['id']) && $data['id']>=1)){
            $data['pass_status'] = 0;
        }
        // 替换品牌
        $data['coding'] = str_replace('{brand}',$data['brand'],$data['coding']);
        if(isset($data['sku']) && is_array($data['sku']) && count($data['sku'])>=1){
            foreach ($data['sku'] as $key=>$item){
                $data['sku'][$key]['coding'] = str_replace('{datetime}',date('Y-m-d H:i:s'),$data['sku'][$key]['coding']);
                $data['sku'][$key]['coding'] = str_replace('{uuid}',Str::uuid(),$data['sku'][$key]['coding']);
            }
            $data['sku'] = json_encode($data['sku'],true);
        }
    }
}
