<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\ViewTagModel;


class TagController extends BaseController
{
    public function list(): BaseView
    {
        $search = $this->_request("search");
        $type = $this->_request("type");
        try {
            $where = null;
            if (!StringHelper::isNullOrBlank($type)) {
                $type = addslashes($type);
                $where = "`type` = '{$type}'";
            }
            $pager = ViewTagModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                $search, $where,
                [$this->_sort()]
            );
            return $this->_apiSuccess($pager);
        } catch (SqlException $e) {
            Mvc::getLogger()->error("标签列表异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }
}