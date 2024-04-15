<?php

namespace Uupt\Erp\Services;

use Uupt\Erp\Models\CompanyClas;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 企业分类
 *
 * @method CompanyClas getModel()
 * @method CompanyClas|\Illuminate\Database\Query\Builder query()
 */
class CompanyClasService extends AdminService
{
    protected string $modelName = CompanyClas::class;
}
