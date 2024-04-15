<?php

namespace Uupt\Erp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Erp\Models\GoodsBrand;
use Uupt\Erp\Services\GoodsBrandService;

/**
 * 商品品牌
 *
 * @property GoodsBrandService $service
 */
class GoodsBrandController extends AdminController
{
    protected string $serviceName = GoodsBrandService::class;

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getBrand(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $lists = GoodsBrand::query()->where('status',1)->select([
            DB::raw('name as label'),
            DB::raw('id as value'),
        ])->orderBy('sort')->get();

        return $this->response()->success($lists);
    }
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
			->headerToolbar([
				$this->createButton(true),
				...$this->baseHeaderToolBar()
			])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
				amis()->TableColumn('name', '品牌名称'),
				amis()->TableColumn('class_name', '品牌分类')->sortable(),
				amis()->TableColumn('desc', '品牌描述'),
				amis()->TableColumn('sort', '排序')->sortable(),
				amis()->TableColumn('logo', '品牌Logo')->type('image'),
                amis()->TableColumn('status', '状态')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
                amis()->TableColumn('website', '官网地址'),
				amis()->TableColumn('created_at', __('admin.created_at'))->set('type', 'datetime')->sortable(),
				amis()->TableColumn('updated_at', __('admin.updated_at'))->set('type', 'datetime')->sortable(),
                $this->rowActions(true)
            ]);

        return $this->baseList($crud);
    }

    /**
     * 操作列
     *
     * @param bool   $dialog
     * @param string $dialogSize
     *
     * @return \Slowlyo\OwlAdmin\Renderers\Operation
     */
    protected function rowActions(bool|array $dialog = false, string $dialogSize = '')
    {
        if (is_array($dialog)) {
            return amis()->Operation()->label(__('admin.actions'))->buttons($dialog);
        }

        return amis()->Operation()->label(__('admin.actions'))->buttons([
//            $this->rowShowButton($dialog, $dialogSize),
            $this->rowEditButton($dialog, $dialogSize),
            $this->rowDeleteButton(),
        ]);
    }

    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->HiddenControl('id','ID')->disabled(),
            amis()->TextControl('name', '品牌名称')->maxLength(15)->required(),
			amis()->TextControl('website', '官网地址')->type('input-url'),
			amis()->SelectControl('class', '品牌分类')->source('/erp/get-brand-class')->required(),
			amis()->TextareaControl('desc', '品牌描述')->required(),
			amis()->NumberControl('sort', '排序')->min(0)->value(0)->required(),
			\UuptImageControl('logo', '品牌Logo')->required(),
            amis()->SwitchControl('status', '是否启用')->trueValue(1)->falseValue(0)->required()
                ->onText('启用')
                ->offText('禁用')
                ->value(1),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('name', '品牌名称')->static(),
			amis()->TextControl('website', '官网地址')->static(),
            amis()->SelectControl('class', '品牌分类')->source('/erp/get-brand-class')->static(),
			amis()->TextControl('desc', '品牌描述')->static(),
			amis()->TextControl('sort', '排序')->static(),
			amis()->ImageControl('logo', '品牌Logo'),
			amis()->SelectControl('status', '品牌状态'),
			amis()->TextControl('created_at', __('admin.created_at'))->static(),
			amis()->TextControl('updated_at', __('admin.updated_at'))->static()
        ]);
    }
}
