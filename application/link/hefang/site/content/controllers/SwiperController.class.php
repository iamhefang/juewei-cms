<?php

namespace link\hefang\site\content\controllers;

use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\SwiperModel;

defined('PROJECT_NAME') or die("Access Refused");


class SwiperController extends BaseController
{
    public function list(): BaseView
    {
        try {
            $pager = SwiperModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                null, '`enable` = TRUE'
            );
            return $this->_apiSuccess($pager);
        } catch (\Throwable $e) {
            return $this->_exception($e);
        }
    }
}