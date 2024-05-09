<?php

namespace ManoCode\Erp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use ManoCode\Erp\Models\BrandClas;
use ManoCode\Erp\Models\Company;
use ManoCode\Erp\Models\CompanyClas;
use ManoCode\Erp\Services\CompanyClasService;

/**
 * 企业分类
 *
 * @property CompanyClasService $service
 */
class CompanyClasController extends AdminController
{
    protected string $serviceName = CompanyClasService::class;

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getCompanyClass(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $lists = CompanyClas::class::query()->select([
            DB::raw('name as label'),
            DB::raw('id as value'),
        ])->where(['status'=>1])->get();
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
				amis()->TableColumn('name', '名称'),
                amis()->TableColumn('status', '状态')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
//				amis()->TableColumn('sort', '排序'),
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
            amis()->HiddenControl('id','ID'),
            amis()->TextControl('name', '名称')->maxLength(15)->required(),
            amis()->SwitchControl('status', '状态')->trueValue(1)->falseValue(0)->required()
                ->onText('启用')
                ->offText('禁用')
                ->value(1),
			amis()->NumberControl('sort', '排序')->min(0)->required()->value(0),
        ]);
    }
}
