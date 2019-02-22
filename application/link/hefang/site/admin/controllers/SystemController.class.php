<?php

namespace link\hefang\site\admin\controllers;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\admin\entities\PollingEntity;

class SystemController extends BaseController
{
    public function polling(): BaseView
    {
        $this->_needLogin();
        return $this->_apiSuccess(new PollingEntity());
    }
}