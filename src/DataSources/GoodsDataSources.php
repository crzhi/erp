<?php

namespace ManoCode\Erp\DataSources;


use Illuminate\Database\Eloquent\Model;
use ManoCode\Approval\Library\DataSourcesAbstract;
use ManoCode\Erp\Http\Controllers\GoodController;
use ManoCode\Erp\Models\Good;
use ManoCode\Erp\Models\GoodsBrand;
use ManoCode\Erp\Models\GoodsClas;

/**
 * 商品变动审批流程
 */
class GoodsDataSources extends DataSourcesAbstract
{
    public function getName():string
    {
        return '商品信息修改审批';
    }
    public function getDescription():string
    {
        return '商品信息修改审批';
    }

    public function getModel(): string
    {
        return Good::class;
    }

    public function getFormComponentsStruct(): array
    {
        return [
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'title',
                    'label'=>'商品标题',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'desc',
                    'label'=>'商品描述',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'coding',
                    'label'=>'商品编码',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'brand',
                    'label'=>'品牌',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'class',
                    'label'=>'分类',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
            [
                'componentType'=>'TextField',
                'props'=>[
                    'componentId'=>'status',
                    'label'=>'状态',
                    'required'=>true, // 必填
                    'disabled'=>false, // 不允许修改
                ]
            ],
        ];
    }

    public function getFormComponentsValue(Model $model): array
    {
        return [
            [
                'name'=>'商品标题',
                'value'=>$model->getAttribute('title')
            ],
            [
                'name'=>'商品描述',
                'value'=>$model->getAttribute('desc')
            ],
            [
                'name'=>'商品编码',
                'value'=>$model->getAttribute('coding')
            ],
            [
                'name'=>'品牌',
                'value'=>GoodsBrand::query()->where(['id'=>$model->getAttribute('brand')])->value('name')
            ],
            [
                'name'=>'分类',
                'value'=>GoodsClas::query()->where(['id'=>$model->getAttribute('class')])->value('name')
            ],
            [
                'name'=>'状态',
                'value'=>array_column(erp_admin_dict_options('goods.status'),'label','value')[$model->getAttribute('status')]
            ],
        ];
    }

    public function pass(Model $model): void
    {
        $model->setAttribute('pass_status',1);
        $model->save();
    }

    public function reject(Model $model): void
    {
        $model->setAttribute('pass_status',2);
        $model->save();
    }

    public function wait(Model $model): void
    {
        $model->setAttribute('pass_status',0);
        $model->save();
    }
}

