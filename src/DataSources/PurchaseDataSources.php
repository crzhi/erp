<?php

namespace Uupt\Erp\DataSources;

use Illuminate\Database\Eloquent\Model;
use Uupt\Approval\Library\DataSourcesAbstract;
use Uupt\Erp\Http\Controllers\GoodController;
use Uupt\Erp\Models\Company;
use Uupt\Erp\Models\Good;
use Uupt\Erp\Models\GoodsBrand;
use Uupt\Erp\Models\GoodsClas;
use Uupt\Erp\Models\Purchase;

/**
 * 采购审批
 */
class PurchaseDataSources extends DataSourcesAbstract
{

    public function getListenEvent(): array
    {
        return [
            'push-process'
        ];
    }

    public function getName():string
    {
        return '采购审批';
    }
    public function getDescription():string
    {
        return '采购审批';
    }

    public function getModel(): string
    {
        return Purchase::class;
    }

    public function getFormComponentsStruct(): array
    {
        return [
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'order_no',
                    'label'=>'采购单号',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'company',
                    'label'=>'供应商',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'pay_type',
                    'label'=>'付款方式',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'admin_remarks',
                    'label'=>'管理备注',
                    'required'=>false, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'order_remarks',
                    'label'=>'采购单备注',
                    'required'=>false, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'contacts',
                    'label'=>'联系人',
                    'required'=>false, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'mobile',
                    'label'=>'联系电话',
                    'required'=>false, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
        ];
    }

    public function getFormComponentsValue(Model $model): array
    {
        return [
            [
                'name'=>'采购单号',
                'value'=>strval($model->getAttribute('order_no'))
            ],
            [
                'name'=>'供应商',
                'value'=>strval(Company::query()->where('id',$model->getAttribute('company'))->value('name'))
            ],
            [
                'name'=>'付款方式',
                'value'=>strval(array_column(erp_admin_dict_options('purchase.pay_type'),'label','value')[$model->getAttribute('pay_type')])
            ],
            [
                'name'=>'采购单备注',
                'value'=>strval($model->getAttribute('order_remarks'))
            ],
            [
                'name'=>'管理备注',
                'value'=>strval($model->getAttribute('admin_remarks'))
            ],
            [
                'name'=>'联系人',
                'value'=>strval($model->getAttribute('contacts'))
            ],
            [
                'name'=>'联系电话',
                'value'=>strval($model->getAttribute('mobile'))
            ],
        ];
    }

    public function pass(Model $model): void
    {
        $model->setAttribute('status',1);
        $model->setAttribute('pass_time',date('Y-m-d H:i:s'));
        $model->setAttribute('start_purchase',date('Y-m-d H:i:s'));
        $model->save();
    }

    public function reject(Model $model): void
    {
        $model->setAttribute('status',4);
        $model->save();
    }

    public function wait(Model $model): void
    {
        $model->setAttribute('status',0);
        $model->save();
    }
}
