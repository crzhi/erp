<?php

namespace Uupt\Erp\Http\Controllers;

use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Erp\Services\PutWarehouseService;

/**
 * 入库表
 *
 * @property PutWarehouseService $service
 */
class PutWarehouseController extends AdminController
{
    protected string $serviceName = PutWarehouseService::class;

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
				amis()->TableColumn('purchase_coding', '采购单号'),
				amis()->TableColumn('company', '供应商'),
				amis()->TableColumn('goods', '产品'),
				amis()->TableColumn('sku', '规格'),
				amis()->TableColumn('number', '数量')->sortable(),
				amis()->TableColumn('remarks', '备注'),
				amis()->TableColumn('created_at', __('admin.created_at'))->set('type', 'datetime')->sortable(),
				amis()->TableColumn('updated_at', __('admin.updated_at'))->set('type', 'datetime')->sortable(),
                $this->rowActions(true)
            ]);

        return $this->baseList($crud);
    }

    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->TextControl('purchase_coding', '采购单号'),
			amis()->TextControl('company', '供应商'),
			amis()->TextControl('goods', '产品'),
			amis()->TextControl('sku', '规格'),
			amis()->TextControl('number', '数量'),
			amis()->TextControl('remarks', '备注'),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('purchase_coding', '采购单号')->static(),
			amis()->TextControl('company', '供应商')->static(),
			amis()->TextControl('goods', '产品')->static(),
			amis()->TextControl('sku', '规格')->static(),
			amis()->TextControl('number', '数量')->static(),
			amis()->TextControl('remarks', '备注')->static(),
			amis()->TextControl('created_at', __('admin.created_at'))->static(),
			amis()->TextControl('updated_at', __('admin.updated_at'))->static()
        ]);
    }
}
