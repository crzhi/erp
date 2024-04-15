<?php

namespace Uupt\Erp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Erp\Models\Good;
use Uupt\Erp\Models\GoodsClas;
use Uupt\Erp\Models\Warehouse;
use Uupt\Erp\Services\GoodsClasService;

/**
 * 商品分类
 *
 * @property GoodsClasService $service
 */
class GoodsClasController extends AdminController
{
    protected string $serviceName = GoodsClasService::class;

    public function getGoodsClassLists()
    {
        $lists = Good::query()->where('status',1)->select([
            DB::raw('title as label'),
            DB::raw('coding as value'),
        ])->get();

        return $this->response()->success($lists);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getGoodsClassTree(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        // 获取顶层文件夹
        $folders = GoodsClas::query()->whereNull('parent_id')->with('recursiveChildren')->get();

        // 转换成树形结构
        $tree = $this->buildTree($folders);

        return $this->response()->success($tree);
    }
    protected function buildTree($folders): array
    {
        $result = [];

        foreach ($folders as $folder) {
            $children = $folder->recursiveChildren;

            $folderData = [
                'label' => $folder->name,
                'value' => $folder->id
            ];

            if ($children->isNotEmpty()) {
                $folderData['children'] = $this->buildTree($children);
            }

            $result[] = $folderData;
        }

        return $result;
    }
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->loadDataOnce()
            ->syncLocation(false)
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->filterTogglable(false)
            ->footerToolbar(['statistics'])
            ->bulkActions([$this->bulkDeleteButton()->reload('window')])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
				amis()->TableColumn('name', '分类名称'),
                amis()->TableColumn('status', '状态')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
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
            amis()->TreeSelectControl('parent_id', '上层ID')->remark('不指定则为顶级分类')->source('/erp/get-goods-class-tree'),
			amis()->TextControl('name', '分类名称')->maxLength(15)->required(),
			amis()->NumberControl('sort', '排序')->min(0)->value(0)->required(),
            amis()->SwitchControl('status', '是否启用')->trueValue(1)->falseValue(0)->required()
                ->onText('启用')
                ->offText('禁用')
                ->value(1),
        ]);
    }
}
