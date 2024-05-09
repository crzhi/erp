<?php

namespace ManoCode\Erp\Http\Controllers;

use Slowlyo\OwlAdmin\Controllers\AdminController;

class ErpController extends AdminController
{
    public function index()
    {
        $page = $this->basePage()->body('进销存管理模块');

        return $this->response()->success($page);
    }
}
