<?php

namespace link\hefang\site\admin\controllers;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\admin\models\ConfigModel;

class ConfigController extends BaseController
{
    public function list(): BaseView
    {
        try {
            $this->_checkAdmin();
            $args = func_get_args();
            $cateId = $this->_request('cateId', CollectionHelper::getOrDefault($args, 0));

            if (StringHelper::isNullOrBlank($cateId)) {
                return $this->_apiFailed("参数异常");
            }
            $pager = ConfigModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(50),
                null,
                "`cate` = '$cateId'",
                [new SqlSort('sort', SqlSort::TYPE_DEFAULT)]
            );
            return $this->_apiSuccess($pager);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("系统配置列表异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function update(): BaseView
    {
        $login = $this->_checkAdmin();
        $data = $this->_post("data");
        if (!is_array($data)) return $this->_apiFailed("参数异常");
        try {
            $res = ConfigModel::alter($data);
            if ($res) {
                Mvc::putConfig(ConfigModel::all(true));
                return $this->_apiSuccess();
            }
            return $this->_apiFailed("未修改任何配置项");
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("修改配置项异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function get(string $id = null): BaseView
    {
        $this->_checkAdmin();
        $id = $this->_request("id", $id);
        return $this->_apiSuccess(Mvc::getConfig($id));
    }
}