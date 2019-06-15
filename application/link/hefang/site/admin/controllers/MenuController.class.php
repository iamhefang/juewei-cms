<?php


namespace link\hefang\site\admin\controllers;


use link\hefang\helpers\ParseHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\interfaces\IDULG;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\admin\models\FunctionModel;

class MenuController extends BaseController implements IDULG
{
    public function list(): BaseView
    {
        $this->_needSuperAdmin();
        $onlyTop = ParseHelper::parseBoolean($this->_request("onlyTop"), false);
        $where = "enable = TRUE";

        if ($onlyTop) {
            $where .= " AND parent_id IS NULL";
        }
        try {
            $pager = FunctionModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                null,
                $where,
                [$this->_sort('sort')]
            );
            return $this->_apiSuccess($pager);
        } catch (SqlException $e) {
            return $this->_exception($e);
        }
    }
}