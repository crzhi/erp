<?php

namespace ManoCode\Erp\Services;

use ManoCode\Erp\Models\Company;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 企业管理
 *
 * @method Company getModel()
 * @method Company|\Illuminate\Database\Query\Builder query()
 */
class CompanyService extends AdminService
{
    protected string $modelName = Company::class;
}
