<?php

namespace Uupt\Erp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Slowlyo\OwlAdmin\Renderers\Page;
use Slowlyo\OwlAdmin\Renderers\Form;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use Uupt\Erp\Models\Company;
use Uupt\Erp\Services\CompanyService;

/**
 * 企业管理
 *
 * @property CompanyService $service
 */
class CompanyController extends AdminController
{
    protected string $serviceName = CompanyService::class;

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function getCompanyLists()
    {
        $lists = Company::query()->select([
            DB::raw('name as label'),
            DB::raw('id as value'),
        ])->where(['status'=>1])->get();
        return $this->response()->success($lists);
    }


    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
				amis()->TableColumn('short_name', '企业简称'),
				amis()->TableColumn('name', '企业名称'),
//				amis()->TableColumn('desc', '企业描述'),
				amis()->TableColumn('class', '企业分类')->sortable(),
//				amis()->TableColumn('sort', '排序')->sortable(),
                amis()->TableColumn('status', '状态')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
                amis()->TableColumn('supplier', '是供应商')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
                amis()->TableColumn('client', '是客户')->quickEdit(
                    amis()->SwitchControl()->mode('inline')->saveImmediately(true)
                ),
				amis()->TableColumn('logo', 'logo')->type('image'),
				amis()->TableColumn('city', '联系城市'),
				amis()->TableColumn('contacts', '联系人'),
				amis()->TableColumn('mobile', '联系电话'),
				amis()->TableColumn('email', '联系邮箱'),
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
                        amis()->TextControl('short_name', '企业简称')->maxLength(150)->required(),
                        amis()->TextControl('name', '企业名称')->maxLength(100)->required(),
                    ]),
                    amis()->TextareaControl('desc', '企业描述')->required(),
                    amis()->GroupControl()->body([
                        amis()->SelectControl('class', '企业分类')->source('/erp/get-company-class'),
                        \UuptImageControl('logo', 'logo'),
                        amis()->NumberControl('sort', '排序')->min(0)->value(0)->required(),
                    ]),
                    amis()->GroupControl()->body([
                        amis()->SwitchControl('status', '状态')->trueValue(1)->falseValue(0)->required(),
                        amis()->SwitchControl('supplier', '是供应商')->trueValue(1)->falseValue(0)->required(),
                        amis()->SwitchControl('client', '是客户')->trueValue(1)->falseValue(0)->required(),
                    ]),
                ]),
                amis()->Tab()->title('联系人信息')->body([
                    amis()->InputCityControl('city', '联系城市')->allowCity()->allowDistrict(),
                    amis()->TextControl('contacts', '联系人'),
                    amis()->TextControl('mobile', '联系电话'),
                    amis()->TextareaControl('address', '详细地址'),
                    amis()->TextControl('email', '联系邮箱'),
                ]),
                amis()->Tab()->title('资质信息')->body([
                    amis()->TextControl('code', '信用代码'),
                    \UuptImageControl('id_img', '营业执照'),
                ]),
                amis()->Tab()->title('银行信息')->body([
                    amis()->TextControl('bank_type', '开户平台'),
                    amis()->TextControl('bank_account', '银行户名'),
                    amis()->TextControl('bank_no', '银行户号'),
                    amis()->TextControl('bank_address', '开户地址'),
                    amis()->SwitchControl('Invoicing', '开发票'),
                ])
            ]),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->Tabs()->tabs([
                amis()->Tab()->title('基础信息')->body([
                    amis()->GroupControl()->body([
                        amis()->TextControl('short_name', '企业简称')->maxLength(150)->static(),
                        amis()->TextControl('name', '企业名称')->maxLength(100)->static(),
                    ])->static(),
                    amis()->TextareaControl('desc', '企业描述')->static(),
                    amis()->GroupControl()->body([
                        amis()->SelectControl('class', '企业分类')->source('/erp/get-company-class')->static(),
                        amis()->TextControl('logo', 'logo')->type('static-image'),
                        amis()->NumberControl('sort', '排序')->min(0)->value(0)->static(),
                    ]),
                    amis()->GroupControl()->body([
                        amis()->SwitchControl('status', '状态')->onText('正常')->offText('禁用')->trueValue(1)->falseValue(0)->static(),
                        amis()->SwitchControl('supplier', '是供应商')->onText('是')->offText('否')->trueValue(1)->falseValue(0)->static(),
                        amis()->SwitchControl('client', '是客户')->onText('是')->offText('否')->trueValue(1)->falseValue(0)->static(),
                    ]),
                ]),
                amis()->Tab()->title('联系人信息')->body([
                    amis()->InputCityControl('city', '联系城市')->allowCity()->allowDistrict()->static(),
                    amis()->TextControl('contacts', '联系人')->static(),
                    amis()->TextControl('mobile', '联系电话')->static(),
                    amis()->TextareaControl('address', '详细地址')->static(),
                    amis()->TextControl('email', '联系邮箱')->static(),
                ]),
                amis()->Tab()->title('资质信息')->body([
                    amis()->TextControl('code', '信用代码')->static(),
                    amis()->TextControl('id_img', '营业执照')->type('static-image'),
                ]),
                amis()->Tab()->title('银行信息')->body([
                    amis()->TextControl('bank_type', '开户平台')->static(),
                    amis()->TextControl('bank_account', '银行户名')->static(),
                    amis()->TextControl('bank_no', '银行户号')->static(),
                    amis()->TextControl('bank_address', '开户地址')->static(),
                    amis()->SwitchControl('Invoicing', '开发票')->onText('是')->offText('否')->static(),
                ])
            ]),
        ]);
    }
}
