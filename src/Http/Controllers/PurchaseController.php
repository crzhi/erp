<?php

namespace Uupt\Erp\Http\Controllers;

use Illuminate\Http\Request;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Approval\Library\DataSourcesManager;
use Uupt\Erp\Models\Purchase;
use Uupt\Erp\Models\PutWarehouse;
use Uupt\Erp\Services\PurchaseService;

/**
 * 采购订单
 *
 * @property PurchaseService $service
 */
class PurchaseController extends AdminController
{
    protected string $serviceName = PurchaseService::class;

    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->filterTogglable()
            ->filter($this->baseFilter()->body([
                amis()->TextControl('coding', '采购编码')->clearable(),
                amis()->TextControl('order_no', '订单号')->clearable(),
                amis()->TreeSelectControl('company')->source('/erp/get-company-lists')->clearable(),
                amis()->SelectControl('pay_type', '付款方式')->options(erp_admin_dict_options('purchase.pay_type'))->clearable(),
                amis()->SelectControl('status')->options(erp_admin_dict_options('purchase.status'))->clearable()
            ]))
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('coding', '采购编码'),
                amis()->TableColumn('order_no', '订单号'),
                amis()->TreeSelectControl('company', '供应商')->source('/erp/get-company-lists')->static(),
                amis()->SelectControl('pay_type', '付款方式')->options(erp_admin_dict_options('purchase.pay_type'))->static()->sortable(),
                amis()->TableColumn('admin_remarks', '管理备注'),
                amis()->TableColumn('order_remarks', '单据备注'),
                amis()->TagControl('status_name', '状态')->color('${status == 3?"success":(status==4?"error":"active")}')->displayMode('status')->type('tag')->static(),
                amis()->TableColumn('contacts', '联系人'),
                amis()->TableColumn('mobile', '联系电话'),
                amis()->TableColumn('created_at', __('admin.created_at'))->set('type', 'datetime')->sortable(),
                amis()->TableColumn('updated_at', __('admin.updated_at'))->set('type', 'datetime')->sortable(),
                $this->rowActions([
                    // 查看 所有状态
                    $this->rowShowButton(true, 'lg')->label('查看'),
                    // 编辑 待审核
                    $this->rowEditButton(true, 'lg')->hiddenOn('${status!=5}'),
                    // 删除 待审核
                    $this->rowDeleteButton()->hiddenOn('${status!=5}'),
                    // 提审
                    amis()->AjaxAction()
                        ->hiddenOn('${status!=5}')
                        ->label('提审')
                        ->icon('fa-regular fa-eye')
                        ->level('link')
                        ->confirmText('您确定要发起审批吗')
                        ->api($this->getPushProcessApi()),
                    // 入库
                    $this->putWarehouse()->hiddenOn('${status!=1 && status !=2}'),
                    // 出库单列表
                    $this->putWarehouseLists()->hiddenOn('${status==0 || status ==1 || status == 4 || status == 5}'),
                ]),
            ]);

        return $this->baseList($crud);
    }
    protected function putWarehouseLists()
    {
        $crud = $this->baseCRUD()
            ->data([
                'coding'=>'${coding}'
            ])
            ->api(admin_url('/put_warehouse?_action=getData&purchase_coding=${coding}'))
            ->filterTogglable(false)
            ->headerToolbar('')
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('purchase_coding', '采购单号'),
                amis()->SelectControl('company', '供应商')->source('/erp/get-company-lists')->static(),
                amis()->SelectControl('goods', '产品')->source('/erp/get-goods-lists-as-code')->static(),
                amis()->TableColumn('sku', '规格'),
                amis()->TableColumn('number', '数量')->sortable(),
                amis()->TableColumn('remarks', '备注'),
                amis()->TableColumn('created_at', __('admin.created_at'))->set('type', 'datetime')->sortable(),
                amis()->TableColumn('updated_at', __('admin.updated_at'))->set('type', 'datetime')->sortable(),
            ]);
        $form = $this->baseList($crud);

        $button = amis()->DialogAction()->dialog(
            amis()->Dialog()->title('入库记录')->body($form)->size('lg')
        );
        return $button->label('入库记录')->icon('fa-regular fa-pen-to-square')->level('link');
    }

    /**
     * 行详情按钮
     *
     * @param bool   $dialog
     * @param string $dialogSize
     *
     * @return \Slowlyo\OwlAdmin\Renderers\DialogAction|\Slowlyo\OwlAdmin\Renderers\LinkAction
     */
    protected function rowShowButton(bool $dialog = false, string $dialogSize = '')
    {
        if ($dialog) {
            $button = amis()->DialogAction()->dialog(
                amis()->Dialog()->title(__('admin.show'))->body($this->detail('$id'))->size($dialogSize)
            );
        } else {
            $button = amis()->LinkAction()->link($this->getShowPath());
        }

        return $button->label(__('admin.show'))->icon('fa-regular fa-eye')->level('link');
    }

    /**
     * 入库
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function putPurchaseWarehouse(Request $request)
    {
        if(!($purchase = Purchase::query()->where('id',$request->post('id'))->whereIn('status',[1,2])->first())){
            return $this->response()->fail('入库失败、采购单不存在');
        }
        $detail = $request->post('detail');
        $idOver = true;
        /**
         * 循环处理
         */
        foreach ($detail as $key=>$item){
            if(isset($item['new_put_number']) && intval($item['new_put_number'])>=1){
                if(isset($detail[$key]['put_number'])){
                    $detail[$key]['put_number'] += intval($item['new_put_number']);
                }else{
                    $detail[$key]['put_number'] = intval($item['new_put_number']);
                }
                if(!(strval($detail[$key]['put_number']) >= intval($item['number']))){
                    $idOver = false;
                }
                PutWarehouse::query()->insert([
                    'purchase_coding'=>$purchase->getAttribute('coding'), // 采购单号
                    'company'=>$purchase->getAttribute('company'), // 采购单号
                    'goods'=>$item['goods'], // 商品编码
                    'sku'=>$item['sku'], // 商品规格
                    'warehouse_position'=>'', //仓位
                    'warehouse'=>$item['warehouse'], // 仓库
                    'number'=>$item['new_put_number'], // 入库数量
                    'remarks'=>isset($item['remarks'])?strval($item['remarks']):'',// 入库备注
                    'created_at'=>date('Y-m-d H:i:s')
                ]);
            }
            unset($detail[$key]['new_put_number']);
        }
        // 状态变更
        if($purchase->getAttribute('status') == 1){
            $purchase->setAttribute('status',2);
        }
        // 开始入库时间
        if(strlen(strval($purchase->getAttribute('start_in_time')))<=0){
            $purchase->setAttribute('start_in_time',date('Y-m-d H:i:s'));
        }
        // 是否完成
        if($idOver){
            $purchase->setAttribute('status',3);
            $purchase->setAttribute('over_time',date('Y-m-d H:i:s'));
        }
        $purchase->setAttribute('detail',json_encode($detail));
        $purchase->save();
        return $this->response()->success([],'入库成功');
    }

    /**
     * 入库表单
     * @return \Slowlyo\OwlAdmin\Renderers\DialogAction
     */
    public function putWarehouse(): \Slowlyo\OwlAdmin\Renderers\DialogAction
    {
        $form = $this->baseForm()->api(admin_url('/erp/put-purchase-warehouse?id=${id}'))->body([
            amis()->HiddenControl('id', 'ID'),
            amis()->GroupControl()->body([
                amis()->TextControl('coding', '采购编码')->static(),
                amis()->TextControl('order_no', '订单号')->static(),
                amis()->TreeSelectControl('company', '供应商')->source('/erp/get-company-lists')->static(),
                amis()->SelectControl('pay_type', '付款方式')->options(erp_admin_dict_options('purchase.pay_type'))->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('admin_remarks', '管理备注')->static(),
                amis()->TextControl('order_remarks', '单据备注')->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('contacts', '联系人')->static(),
                amis()->TextControl('mobile', '联系电话')->static(),
            ]),
            // 详情
            amis()->TextControl('detail', '采购详情')->type('input-table')->set('minLength', 1)
                ->set('columns', [
                    amis()->SelectControl('warehouse', '仓库')->source('/erp/get-store-lists')->searchable()->clearable()->required(),
                    amis()->SelectControl('warehouse_position', '仓位')->source('/erp/get-store-position?id=${warehouse}')->searchable()->clearable()->required(),
                    amis()->NumberControl('new_put_number', '入库数量')->value(0)->max('${number - put_number}')->required(),
                    amis()->TextareaControl('remarks', '备注')->width(300),
                    amis()->NumberControl('put_number', '已入库数量')->static(),
                    amis()->SelectControl('goods', '商品')->searchable()->clearable()->source('/erp/get-goods-lists')->static(),
                    amis()->TextControl('coding', '产品编码')->value('${goods}')->static()->static(),
                    amis()->SelectControl('sku', '规格')->source('/erp/get-goods-sku?id=${goods}')->static(),
                    amis()->NumberControl('number', '采购数量')->required()->static(),
                    amis()->NumberControl('price', '采购价格')->precision(2)->required()->static(),
                    amis()->NumberControl('tax_money', '税金')->precision(2)->static(),
                    amis()->NumberControl('all_money', '采购总价')->precision(2)->value('${price*number}')->static(),
                    amis()->TextControl('remarks', '备注')->static(),
                ]),
        ])
            ->initApi($this->getShowPath())
            ->redirect('')
            ->onEvent([]);

        $button = amis()->DialogAction()->dialog(
            amis()->Dialog()->title('采购入库')->body($form)->size('lg')
        );

        return $button->label('入库')->icon('fa-regular fa-pen-to-square')->level('link');
    }

    /**
     * 推送采购审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function pushProcessApi(Request $request)
    {
        if (!($purchase = Purchase::query()->where(['id' => $request->input('id'), 'status' => 5])->first())) {
            return $this->response()->fail('采购订单不存在');
        }
        /**
         * 触发 自定义推送事件 时间内会自动修改状态为 待审核
         */
        DataSourcesManager::getInstance()->triggerEvent('push-process', $purchase);
        return $this->response()->success([], '提审成功');
    }

    /**
     * 推送审核
     * @return string
     */
    public function getPushProcessApi(): string
    {
        $primaryKey = isset($this->service) ? $this->service->primaryKey() : 'id';

        return 'post:' . admin_url("/erp/push-purchase-process?id=\${$primaryKey}");
    }

    public function form($isEdit = false): Form
    {
        $templateTpl = '可自定义编码，默认自动生成。可用变量：{datetime} , {uuid},例如：order-{uuid}';
        return $this->baseForm()->body([
            amis()->HiddenControl('id', 'ID'),
            amis()->GroupControl()->body([
                amis()->TextControl('coding', '采购编码')->value('order-{uuid}')->remark($templateTpl)->required(),
                amis()->TreeSelectControl('company', '供应商')->source('/erp/get-company-lists')->required(),
                amis()->SelectControl('pay_type', '付款方式')->options(erp_admin_dict_options('purchase.pay_type'))->required(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('admin_remarks', '管理备注'),
                amis()->TextControl('order_remarks', '单据备注'),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('contacts', '联系人'),
                amis()->TextControl('mobile', '联系电话'),
            ]),
            // 详情
            amis()->TextControl('detail', '采购详情')->type('input-table')->set('minLength', 1)
                ->set('maxLength', 50)
                ->set('copyBtnLabel', '复制')
                ->set('editBtnLabel', '编辑')
                ->set('copyable', true)
                ->set('addable', true)
                ->set('editable', true)
                ->value([
                    [
                        'number' => 1
                    ]
                ])
                ->set('columns', [
                    amis()->SelectControl('goods', '商品')->searchable()->clearable()->source('/erp/get-goods-lists'),
                    amis()->TextControl('coding', '产品编码')->value('${goods}')->static(),
                    amis()->SelectControl('sku', '规格')->source('/erp/get-goods-sku?id=${goods}'),
                    amis()->NumberControl('number', '采购数量')->required(),
                    amis()->NumberControl('price', '采购价格')->precision(2)->required(),
                    amis()->NumberControl('tax_money', '税金')->precision(2),
                    amis()->NumberControl('all_money', '采购总价')->precision(2)->value('${price*number}')->required(),
                    amis()->TextControl('remarks', '备注'),
                ]),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseForm(false)->body([
            amis()->HiddenControl('id', 'ID'),
            amis()->GroupControl()->body([
                amis()->TextControl('coding', '采购编码')->static(),
                amis()->TextControl('order_no', '订单号')->static(),
                amis()->TreeSelectControl('company', '供应商')->source('/erp/get-company-lists')->static(),
                amis()->SelectControl('pay_type', '付款方式')->options(erp_admin_dict_options('purchase.pay_type'))->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('admin_remarks', '管理备注')->static(),
                amis()->TextControl('order_remarks', '单据备注')->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('contacts', '联系人')->static(),
                amis()->TextControl('mobile', '联系电话')->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('start_in_time', '开始入库时间')->static(),
                amis()->TextControl('over_time', '完成时间')->static(),
                amis()->TextControl('start_purchase', '开始采购时间')->static(),
            ]),
            amis()->GroupControl()->body([
                amis()->TextControl('status_name', '状态')->type('tag')->color('${status == 3?"success":(status==4?"error":"active")}')->displayMode('status')->type('tag')->static(),
                amis()->TextControl('pass_time', '审批时间')->static(),
            ]),
            // 详情
            amis()->TextControl('detail', '采购详情')->type('input-table')->set('minLength', 1)->static()
                ->set('columns', [
                    amis()->NumberControl('put_number', '已入库数量')->static(),
                    amis()->SelectControl('goods', '商品')->searchable()->clearable()->source('/erp/get-goods-lists')->static(),
                    amis()->TextControl('coding', '产品编码')->value('${goods}')->static(),
                    amis()->SelectControl('sku', '规格')->source('/erp/get-goods-sku?id=${goods}')->static(),
                    amis()->NumberControl('number', '采购数量')->static(),
                    amis()->NumberControl('price', '采购价格')->precision(2)->static(),
                    amis()->NumberControl('tax_money', '税金')->precision(2)->static(),
                    amis()->NumberControl('all_money', '采购总价')->precision(2)->value('${price*number}')->static(),
                    amis()->TextControl('remarks', '备注')->static(),
                ]),
        ]);
    }
}
