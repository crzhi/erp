<?php

namespace ManoCode\Erp\Services;

use ManoCode\Erp\Models\BrandClas;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 品牌分类
 *
 * @method BrandClas getModel()
 * @method BrandClas|\Illuminate\Database\Query\Builder query()
 */
class BrandClasService extends AdminService
{
    protected string $modelName = BrandClas::class;

}
