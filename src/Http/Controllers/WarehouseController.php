<?php

namespace Uupt\Erp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Erp\Models\GoodsBrand;
use Uupt\Erp\Models\Warehouse;
use Uupt\Erp\Services\WarehouseService;

/**
 * 仓库管理
 *
 * @property WarehouseService $service
 */
class WarehouseController extends AdminController
{
    protected string $serviceName = WarehouseService::class;

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getStoreLists(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $lists = Warehouse::query()->where('status',1)->select([
            DB::raw('name as label'),
            DB::raw('id as value'),
        ])->orderBy('sort')->get();

        return $this->response()->success($lists);
    }

    /**
     * 获取仓位
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getStorePosition(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $lists = [];
        if($warehouse = Warehouse::query()->where(['id'=>$request->input('id')])->first()){
            foreach (json_decode($warehouse->getAttribute('position'),true) as $item){
                $lists[] = [
                    'label'=>$item['item'],
                    'value'=>$item['item'],
                ];
            }
        }

        return $this->response()->success($lists);
    }
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
				amis()->TableColumn('name', '名称'),
				amis()->TableColumn('alias', '别名'),
				amis()->InputCityControl('city', '所在区域')->static(),
				amis()->TableColumn('address', '详细地址'),
				amis()->TableColumn('contacts', '联系人'),
				amis()->TableColumn('mobile', '联系电话'),
                amis()->TableColumn('status', '状态')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
				amis()->TableColumn('created_at', __('admin.created_at'))->set('type', 'datetime')->sortable(),
				amis()->TableColumn('updated_at', __('admin.updated_at'))->set('type', 'datetime')->sortable(),
                $this->rowActions([
                    $this->rowShowButton(true, 'lg'),
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->HiddenControl('id','ID'),
            amis()->Tabs()->tabs([
                amis()->Tab()->title('基础信息')->body([
                    amis()->GroupControl()->body([
                        amis()->TextControl('name', '名称')->maxLength(100)->required(),
                        amis()->TextControl('alias', '别名')->maxLength(100),
                    ]),
                    amis()->TextareaControl('desc', '仓库描述'),
                    amis()->GroupControl()->body([
                        amis()->NumberControl('sort', '排序')->min(0)->required()->value(0),
                        amis()->SwitchControl('status', '状态')->trueValue(1)->falseValue(0)->value(1)->required(),
                        amis()->TreeSelectControl('class', '仓库分类')->source('/erp/get-store-class-tree')->required(),
                    ]),
                ]),
                amis()->Tab()->title('联系人')->body([
                    amis()->InputCityControl('city', '所在区域')->allowCity()->allowDistrict(),
                    amis()->TextareaControl('address', '详细地址'),
                    amis()->TextControl('contacts', '联系人'),
                    amis()->TextControl('mobile', '联系电话'),
                    amis()->TextControl('email', '邮箱'),
                ]),
                amis()->Tab()->title('仓位')->body([
                    amis()->TextControl('position', '仓位')->required()->type('input-table')
                        ->set('minLength',1)
                        ->set('maxLength',50)
                        ->set('copyBtnLabel','复制仓位')
                        ->set('editBtnLabel','编辑仓位')
                        ->set('copyable',true)
                        ->set('addable',true)
                        ->set('editable',true)
                        ->value([
                            [
                                'item'=>'默认仓位'
                            ]
                        ])
                        ->set('columns',[
                        [
                            'label'=>'名称',
                            'name'=>'item',
                            "required"=>true,
                        ]
                    ]),
                ]),
            ]),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->Tabs()->tabs([
                amis()->Tab()->title('基础信息')->body([
                    amis()->GroupControl()->body([
                        amis()->TextControl('name', '名称')->maxLength(100)->static(),
                        amis()->TextControl('alias', '别名')->maxLength(100)->static(),
                    ]),
                    amis()->TextareaControl('desc', '仓库描述')->static(),
                    amis()->GroupControl()->body([
                        amis()->SwitchControl('status', '状态')->onText('正常')->offText('禁用')->trueValue(1)->falseValue(0)->value(1)->static(),
                        amis()->TreeSelectControl('class', '仓库分类')->source('/erp/get-store-class-tree')->static(),
                    ]),
                ]),
                amis()->Tab()->title('联系人')->body([
                    amis()->InputCityControl('city', '所在区域')->static(),
                    amis()->TextareaControl('address', '详细地址')->static(),
                    amis()->TextControl('contacts', '联系人')->static(),
                    amis()->TextControl('mobile', '联系电话')->static(),
                    amis()->TextControl('email', '邮箱')->static(),
                ]),
                amis()->Tab()->title('仓位')->body([
                    amis()->TextControl('position', '仓位')->static(),
                ]),
            ]),
        ]);
    }
}
