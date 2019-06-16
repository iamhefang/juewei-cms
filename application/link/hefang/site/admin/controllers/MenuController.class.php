<?php


namespace link\hefang\site\admin\controllers;


use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
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

    public function update(): BaseView
    {
        $this->_needSuperAdmin();
        $id = $this->_request("id");
        $label = $this->_post("label");
        $parentId = $this->_post("parentId");
        $sort = $this->_post("sort");
        $icon = $this->_post("icon");
        $link = $this->_post("link");


        if (StringHelper::isNullOrBlank($id)) {
            return $this->_apiFailed('参数异常');
        }
        try {
            $menu = FunctionModel::get($id);
            if (!($menu instanceof FunctionModel)) {
                return $this->_apiFailed("该菜单不存在或已被删除");
            }
            $res = $menu->setLabel($label)
                ->setParentId($parentId)
                ->setSort($sort)
                ->setIcon($icon)
                ->setLink($link)
                ->update(['label', 'parent_id', 'sort', 'icon', 'link']);
            return $res ? $this->_apiSuccess($res) : $this->_apiFailed('更新未成功');
        } catch (\Throwable $e) {
            return $this->_exception($e);
        }
    }
}