<?php

namespace Uupt\Erp;

use Slowlyo\OwlAdmin\Extend\Extension;
use Slowlyo\OwlAdmin\Models\AdminMenu;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlAdmin\Extend\ServiceProvider;
use Slowlyo\OwlDict\AdminDict;
use Uupt\Approval\Library\DataSourcesManager;
use Uupt\Erp\DataSources\GoodsDataSources;
use Uupt\Erp\DataSources\PurchaseDataSources;

class ErpServiceProvider extends ServiceProvider
{
    public function install()
    {
        parent::install();
        // 安装字典数据
        $this->installDict();
        // 安装菜单数据
        $this->installMenu();
    }
    protected function installMenu(): void
    {
        // ERP 管理
        $root_id = AdminMenu::query()->insertGetId([
            'parent_id' => 0,
            'order' => 0,
            'title' => 'ERP模块',
            'icon' => 'carbon:network-enterprise',
            'url' => '/erp',
            'url_type' => 1,
            'visible' => 1,
            'is_home' => 0,
            'keep_alive' => 0,
            'iframe_url' => NULL,
            'component' => 'amis',
            'is_full' => 0,
            'extension' => NULL,
        ]);
        // 商品管理
        $goods_node_id = AdminMenu::query()->insertGetId([
            'parent_id' => $root_id,
            'order' => 0,
            'title' => '商品模块',
            'icon' => 'ep:goods',
            'url' => '/erp/goods',
            'url_type' => 1,
            'visible' => 1,
            'is_home' => 0,
            'keep_alive' => 0,
            'iframe_url' => NULL,
            'component' => 'amis',
            'is_full' => 0,
            'extension' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        AdminMenu::query()->insert([
            [
                'parent_id' => $goods_node_id,
                'order' => 100,
                'title' => '商品管理',
                'icon' => 'streamline:shopping-bag-hand-bag-2-shopping-bag-purse-goods-item-products',
                'url' => '/goods',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $goods_node_id,
                'order' => 100,
                'title' => '商品分类',
                'icon' => 'arcticons:anytype',
                'url' => '/goods_class',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $goods_node_id,
                'order' => 100,
                'title' => '计量单位',
                'icon' => 'pajamas:issue-type-objective',
                'url' => '/goods_unit',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $goods_node_id,
                'order' => 100,
                'title' => '商品品牌',
                'icon' => 'brandico:bandcamp',
                'url' => '/goods_brand',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $goods_node_id,
                'order' => 100,
                'title' => '品牌分类',
                'icon' => 'iconoir:plug-type-l',
                'url' => '/brand_class',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);

        // 供应商管理
        $company_node_id = AdminMenu::query()->insertGetId([
            'parent_id' => $root_id,
            'order' => 0,
            'title' => '供应商管理',
            'icon' => 'arcticons:microsoft-company-portal',
            'url' => '/companys',
            'url_type' => 1,
            'visible' => 1,
            'is_home' => 0,
            'keep_alive' => 0,
            'iframe_url' => NULL,
            'component' => 'amis',
            'is_full' => 0,
            'extension' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        AdminMenu::query()->insert([
            [
                'parent_id' => $company_node_id,
                'order' => 100,
                'title' => '企业分类',
                'icon' => 'material-symbols-light:type-specimen-outline',
                'url' => '/company_class',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $company_node_id,
                'order' => 0,
                'title' => '企业管理',
                'icon' => 'arcticons:microsoft-company-portal',
                'url' => '/company',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => 'amis',
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
        // 仓库管理
        $store_node_id = AdminMenu::query()->insertGetId([
            'parent_id' => $root_id,
            'order' => 0,
            'title' => '仓库管理',
            'icon' => 'fa-solid:warehouse',
            'url' => '/erp/store',
            'url_type' => 1,
            'visible' => 1,
            'is_home' => 0,
            'keep_alive' => 0,
            'iframe_url' => NULL,
            'component' => 'amis',
            'is_full' => 0,
            'extension' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        AdminMenu::query()->insert([
            [
                'parent_id' => $store_node_id,
                'order' => 100,
                'title' => '仓库管理',
                'icon' => 'ant-design:appstore-twotone',
                'url' => '/warehouse',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $store_node_id,
                'order' => 100,
                'title' => '仓库分类',
                'icon' => 'ph:circle-half-tilt-light',
                'url' => '/warehouse_class',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
        // 采购管理
        $purchase_node_id = AdminMenu::query()->insertGetId([
            'parent_id' => $root_id,
            'order' => 0,
            'title' => '采购管理',
            'icon' => 'bx:purchase-tag',
            'url' => '/purchase-model',
            'url_type' => 1,
            'visible' => 1,
            'is_home' => 0,
            'keep_alive' => 0,
            'iframe_url' => NULL,
            'component' => 'amis',
            'is_full' => 0,
            'extension' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        AdminMenu::query()->insert([
            [
                'parent_id' => $purchase_node_id,
                'order' => 100,
                'title' => '采购订单',
                'icon' => 'bx:bxs-purchase-tag-alt',
                'url' => '/purchase',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'parent_id' => $purchase_node_id,
                'order' => 100,
                'title' => '入库管理',
                'icon' => 'ph:circle',
                'url' => '/put_warehouse',
                'url_type' => 1,
                'visible' => 0,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => NULL,
                'component' => NULL,
                'is_full' => 0,
                'extension' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
    protected function installDict()
    {
        $this->insertMenuOrUpdate('uupt.erp.purchase.status','采购状态',[
            [
                'key' => '0',
                'enabled' => 1,
                'sort' => 0,
                'value' => '待审核',
                'created_at' => '2024-04-13 01:10:58',
                'updated_at' => '2024-04-13 01:10:58',

            ],
            [
                'key' => '1',
                'enabled' => 1,
                'sort' => 0,
                'value' => '采购中',
                'created_at' => '2024-04-13 01:11:04',
                'updated_at' => '2024-04-13 01:11:04',

            ],
            [
                'key' => '2',
                'enabled' => 1,
                'sort' => 0,
                'value' => '入库中',
                'created_at' => '2024-04-13 01:11:12',
                'updated_at' => '2024-04-13 01:11:12',

            ],
            [
                'key' => '3',
                'enabled' => 1,
                'sort' => 0,
                'value' => '已完成',
                'created_at' => '2024-04-13 01:11:20',
                'updated_at' => '2024-04-13 01:11:20',

            ],
            [
                'key' => '4',
                'enabled' => 1,
                'sort' => 0,
                'value' => '已拒绝',
                'created_at' => '2024-04-13 01:11:26',
                'updated_at' => '2024-04-13 01:11:26',

            ],
            [
                'key' => '5',
                'enabled' => 1,
                'sort' => 0,
                'value' => '待提交',
                'created_at' => '2024-04-13 01:28:19',
                'updated_at' => '2024-04-13 01:28:19',
            ]
        ]);
        $this->insertMenuOrUpdate('uupt.erp.purchase.pay_type','采购支付类型',[
            [
                'key' => '0',
                'enabled' => 1,
                'sort' => 0,
                'value' => '未确定',
                'created_at' => '2024-04-13 01:12:01',
                'updated_at' => '2024-04-13 01:12:01',

            ],
            [
                'key' => '1',
                'enabled' => 1,
                'sort' => 0,
                'value' => '账期结算',
                'created_at' => '2024-04-13 01:12:08',
                'updated_at' => '2024-04-13 01:12:08',

            ],
            [
                'key' => '2',
                'enabled' => 1,
                'sort' => 0,
                'value' => '预付款',
                'created_at' => '2024-04-13 01:12:13',
                'updated_at' => '2024-04-13 01:12:13',

            ],
            [
                'key' => '3',
                'enabled' => 1,
                'sort' => 0,
                'value' => '银行转账',
                'created_at' => '2024-04-13 01:12:20',
                'updated_at' => '2024-04-13 01:12:20',

            ],
            [
                'key' => '4',
                'enabled' => 1,
                'sort' => 0,
                'value' => '现金支付',
                'created_at' => '2024-04-13 01:12:26',
                'updated_at' => '2024-04-13 01:12:26',

            ],
            [
                'key' => '5',
                'enabled' => 1,
                'sort' => 0,
                'value' => '在线支付',
                'created_at' => '2024-04-13 01:12:33',
                'updated_at' => '2024-04-13 01:12:33',

            ],
            [
                'key' => '6',
                'enabled' => 1,
                'sort' => 0,
                'value' => '其他方式',
                'created_at' => '2024-04-13 01:12:39',
                'updated_at' => '2024-04-13 01:12:39',

            ],
        ]);
        $this->insertMenuOrUpdate('uupt.erp.goods.status','商品状态',[
            [
                'key' => '1',
                'enabled' => 1,
                'sort' => 0,
                'value' => '正常',
                'created_at' => '2024-04-13 01:22:46',
                'updated_at' => '2024-04-13 01:22:46',

            ],
            [
                'key' => '2',
                'enabled' => 1,
                'sort' => 0,
                'value' => '下架',
                'created_at' => '2024-04-13 01:22:55',
                'updated_at' => '2024-04-13 01:22:55',

            ],
            [
                'key' => '3',
                'enabled' => 1,
                'sort' => 0,
                'value' => '停售',
                'created_at' => '2024-04-13 01:23:01',
                'updated_at' => '2024-04-13 01:23:01',

            ],
            [
                'key' => '4',
                'enabled' => 1,
                'sort' => 0,
                'value' => '停产',
                'created_at' => '2024-04-13 01:23:07',
                'updated_at' => '2024-04-13 01:23:07',

            ],
        ]);
        $this->insertMenuOrUpdate('uupt.erp.goods.pass_status','商品审核状态',[
            [
                'key' => '0',
                'enabled' => 1,
                'sort' => 0,
                'value' => '待审核',
                'created_at' => '2024-04-13 01:23:21',
                'updated_at' => '2024-04-13 01:23:21',

            ],
            [
                'key' => '1',
                'enabled' => 1,
                'sort' => 0,
                'value' => '已通过',
                'created_at' => '2024-04-13 01:23:24',
                'updated_at' => '2024-04-13 01:23:24',

            ],
            [
                'key' => '2',
                'enabled' => 1,
                'sort' => 0,
                'value' => '已拒绝',
                'created_at' => '2024-04-13 01:23:29',
                'updated_at' => '2024-04-13 01:23:29',

            ],
        ]);
    }
    protected function insertMenuOrUpdate(string $key,string $name ='',array $lists=[]): void
    {
        if(!($purchase_status_id = \Slowlyo\OwlDict\Models\AdminDict::query()->where(['key'=>$key,'value'=>$name])->value('id'))){
            $purchase_status_id = \Slowlyo\OwlDict\Models\AdminDict::query()->insertGetId([
                'parent_id' => '0',
                'key' => $key,
                'enabled' => 1,
                'sort' => 0,
                'value' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        foreach ($lists as $item){
            if(!\Slowlyo\OwlDict\Models\AdminDict::query()->where(['parent_id'=>$purchase_status_id,'key'=>$key,'value'=>$item['value']])->first()){
                $item['parent_id'] = $purchase_status_id;
                $item['created_at'] = date('Y-m-d H:i:s');
                $item['updated_at'] = date('Y-m-d H:i:s');
                \Slowlyo\OwlDict\Models\AdminDict::query()->insert($item);
            }
        }
    }
    public function boot()
    {
        DataSourcesManager::getInstance()->registerDataSources(new GoodsDataSources());
        DataSourcesManager::getInstance()->registerDataSources(new PurchaseDataSources());
        if (Extension::tableExists()) {
            $this->autoRegister();

            $this->init();
        }
    }

	public function settingForm()
	{
	    return $this->baseSettingForm()->body([
            TextControl::make()->name('value')->label('Value')->required(true),
	    ]);
	}
}
