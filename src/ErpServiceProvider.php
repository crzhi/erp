<?php

namespace Uupt\Erp;

use Illuminate\Support\Facades\Cache;
use Slowlyo\OwlAdmin\Extend\Extension;
use Slowlyo\OwlAdmin\Models\AdminMenu;
use Slowlyo\OwlAdmin\Renderers\TextControl;
use Slowlyo\OwlAdmin\Extend\ServiceProvider;
use Slowlyo\OwlDict\AdminDict;
use Slowlyo\OwlDict\Models\AdminDict as AdminDictModel;
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
        // 清空字典缓存
        Cache::forget('admin_dict_cache_key');
        Cache::forget('admin_dict_valid_cache_key');
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

    /**
     * 安装字典
     * @return void
     */
    protected function installDict(): void
    {
        $dicts = [
            [
                'key' => 'uupt.erp.purchase.status',
                'value' => '采购状态',
                'keys' => [
                    ['key' => 0, 'value' => '待审核'],
                    ['key' => 1, 'value' => '采购中'],
                    ['key' => 2, 'value' => '入库中'],
                    ['key' => 3, 'value' => '已完成'],
                    ['key' => 4, 'value' => '已拒绝'],
                    ['key' => 5, 'value' => '待提交'],
                ]
            ],
            [
                'key' => 'uupt.erp.purchase.pay_type',
                'value' => '采购支付类型',
                'keys' => [
                    ['key' => "0", 'value' => '未确定'],
                    ['key' => "1", 'value' => '账期结算'],
                    ['key' => "2", 'value' => '预付款'],
                    ['key' => "3", 'value' => '银行转账'],
                    ['key' => "4", 'value' => '现金支付'],
                    ['key' => "5", 'value' => '在线支付'],
                    ['key' => "6", 'value' => '其他方式'],
                ]
            ],
            [
                'key' => 'uupt.erp.goods.status',
                'value' => '商品状态',
                'keys' => [
                    ['key' => "1", 'value' => '正常'],
                    ['key' => "2", 'value' => '下架'],
                    ['key' => "3", 'value' => '停售'],
                    ['key' => "4", 'value' => '停产'],
                ]
            ],
            [
                'key' => 'uupt.erp.goods.pass_status',
                'value' => '商品审核状态',
                'keys' => [
                    ['key' => "0", 'value' => '待审核'],
                    ['key' => "1", 'value' => '已通过'],
                    ['key' => "2", 'value' => '已拒绝'],
                ]
            ],
        ];
        foreach ($dicts as $dict) {
            $dictModel = AdminDictModel::query()->where('key', $dict['key'])->first();
            if (!$dictModel) {
                $dictModel = new AdminDictModel();
                $dictModel->value = $dict['value'];
                $dictModel->enabled = 1;
                $dictModel->key = $dict['key'];
                $dictModel->save();
            }
            foreach ($dict['keys'] as $value) {
                $dictValueModel = AdminDictModel::query()->where('parent_id', $dictModel->id)->where('key', $value['key'])->first();
                if (!$dictValueModel) {
                    $dictValueModel = new AdminDictModel();
                    $dictValueModel->parent_id = $dictModel->id;
                    $dictValueModel->key = $value['key'];
                    $dictValueModel->value = $value['value'];
                    $dictValueModel->enabled = 1;
                    $dictValueModel->save();
                }
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
