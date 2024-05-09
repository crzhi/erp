<?php

namespace ManoCode\Erp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use ManoCode\Erp\Models\Good;
use ManoCode\Erp\Models\GoodsUnit;
use ManoCode\Erp\Services\GoodService;
use Illuminate\Http\Request;

/**
 * 商品管理
 *
 * @property GoodService $service
 */
class GoodController extends AdminController
{
    protected string $serviceName = GoodService::class;

    public function getGoodsLists(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Good::query();
        if(strlen(strval($request->input('term')))>=1){
            $query->where(function($where) use($request){
                $where->where('title','like',"%{$request->input('term')}%")->orWhere('desc','like',"%{$request->input('term')}%");
            });
        }
        return response()->json([
            'options'=>$query->select([
                DB::raw('title as label'),
                DB::raw('coding as value'),
            ])->get()
        ]);
    }
    public function getGoodsSku(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = [];
        if(!($request->has('id') && strlen($request->input('id'))>=1)){
            return response()->json([
                'options'=>$options
            ]);
        }
        if(!(($goods = Good::query()->where('coding',($request->input('id')))->first()) && strlen($goods->getAttribute('sku')>=1))){
            return response()->json([
                'options'=>$options
            ]);
        }
        foreach (json_decode($goods->getAttribute('sku'),true) as $key=>$item){
            $unitName = GoodsUnit::query()->where('id',$item['unit'])->value('name');
            $options[] = [
                'label'=>"{$unitName} X {$item['base_num']}",
                'value'=>$item['coding']
            ];
        }

        return response()->json([
            'options'=>$options
        ]);
    }


    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(true)
            ->filter($this->baseFilter()->body([
                amis()->GroupControl()->body([
                    amis()->TextControl('title', '标题')->clearable(),
                    amis()->TextControl('desc', '描述')->clearable(),
                ]),
                amis()->GroupControl()->body([
                    amis()->TreeSelectControl('class', '商品分类')->width(400)->source('/erp/get-goods-class-tree')->clearable(),
                    amis()->SelectControl('brand', '品牌')->width(400)->source('/erp/get-brand')->clearable(),
                ]),
            ]))
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->data([
                'pass_status_names'=>array_column(erp_admin_dict_options('goods.pass_status'),'value','label')
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
				amis()->TableColumn('title', '标题'),
				amis()->TableColumn('coding', '编码'),
				amis()->TableColumn('brand', '品牌')->sortable(),
				amis()->TableColumn('class', '商品分类')->sortable(),
				amis()->TableColumn('desc', '商品描述'),
                amis()->TagControl('status_name','状态')->color('${status==1?"success":"active"}')->displayMode('status')->type('tag')->static(),
                amis()->TagControl('pass_status_name','审批')->color('${pass_status==1?"success":(pass_status==0?"active":"error")}')->displayMode('status')->type('tag')->static(),
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
        if(!$isEdit){
            $templateTpl = '可自定义编码，默认自动生成。可用变量：{datetime} , {type} , {brand} ,{uuid},例如：goods-{brand}-{datetime}';
        }else{
            $templateTpl = '';
        }
        return $this->baseForm()->body([
            amis()->HiddenControl('id','ID')->hidden(),
            amis()->Tabs()->tabs([
                // 基础信息
                amis()->Tab()->title('基础信息')->body([
                    amis()->TextControl('title', '标题')->required(),
                    amis()->TextControl('coding', '编码')->required()->disabled($isEdit)->remark($templateTpl)->value($isEdit?'':'goods-{type}-{brand}-{datetime}'),
                    amis()->SelectControl('brand', '品牌')->source('/erp/get-brand')->required(),
                    amis()->TreeSelectControl('class', '商品分类')->source('/erp/get-goods-class-tree')->required(),
                    amis()->TextareaControl('desc', '商品描述')->required(),
                    amis()->SelectControl('status','状态')->options(erp_admin_dict_options('goods.status'))->value('1'),
                ]),
                // 商品详情
                amis()->Tab()->title('商品详情')->body([
                    \ManoRichTextControl('rich_text', '介绍描述内容')->required(),
                ]),
                // 规格信息
                amis()->Tab()->title('规格信息')->body([
                    amis()->TextControl('sku','规格/计量单位')->type('input-table')
                        ->set('minLength',1)
                        ->set('maxLength',50)
                        ->set('copyBtnLabel','复制')
                        ->set('editBtnLabel','编辑')
                        ->set('copyable',true)
                        ->set('addable',true)
                        ->set('editable',true)
                        ->value([
                            [
                                'unit'=>'',
                                'base_num'=>1,
                                'coding'=>'sku-{uuid}',
                                'weight'=>''
                            ]
                        ])->set('columns',[
                            [
                                'label'=>'单位',
                                'name'=>'unit',
                                "required"=>true,
                                'type'=>'select',
                                'source'=>admin_url('/erp/get-goods-unit')
                            ],
                            [
                                'label'=>'基准数',
                                'name'=>'base_num',
                                "required"=>true,
                            ],
                            [
                                'label'=>'编码',
                                'name'=>'coding',
                                'remark'=>'可自定义编码，默认自动生成。可用变量：{datetime} , {uuid},例如：sku-{uuid}',
                                "required"=>true,
                            ],
                            [
                                'label'=>'重量(kg)',
                                'name'=>'weight',
                                "required"=>false,
                            ],
                            [
                                'label'=>'体积(m³)',
                                'name'=>'volume',
                                "required"=>false,
                            ],
                            [
                                'label'=>'零售价',
                                'name'=>'retail_price',
                                "required"=>true,
                            ],
                            [
                                'label'=>'成本价',
                                'name'=>'cost',
                                "required"=>true,
                            ],
                        ])
                ]),
            ]),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('title', '标题')->static(),
            amis()->TextControl('coding', '编码')->static(),
            amis()->SelectControl('brand', '品牌')->source('/erp/get-brand')->static(),
            amis()->TreeSelectControl('class', '商品分类')->source('/erp/get-goods-class-tree')->static(),
			amis()->TextareaControl('desc', '商品描述')->static(),
			amis()->TextControl('status_name', '商品状态')->static(),
			amis()->TextControl('pass_status_name', '审批状态')->static(),
			amis()->RichTextControl('rich_text', '富文本内容')->static(),
			amis()->TextControl('created_at', __('admin.created_at'))->static(),
			amis()->TextControl('updated_at', __('admin.updated_at'))->static()
        ]);
    }
}
