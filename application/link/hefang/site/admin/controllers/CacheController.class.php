<?php

namespace link\hefang\site\admin\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class CacheController extends BaseController
{
    public function clean(): BaseView
    {
        $this->_checkSuperAdmin();
        $res = Mvc::getCache()->clean();
        return $res ? $this->_apiSuccess() : $this->_apiFailed("清空缓存失败");
    }
}