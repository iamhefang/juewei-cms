<?php

namespace link\hefang\site\statistics\controllers;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\statistics\models\LogVisitModel;

class VisitController extends BaseController
{
    const PATTERN_TIME = '/^\d{4}-\d{2}-\d{2}$/';

    public function delete(): BaseView
    {
        $this->_checkSuperAdmin();
        $id = $this->_post("id");
        if (!is_array($id)) return $this->_apiFailed('参数异常');

        $ids = "'" . join("','", $id) . "'";

        try {
            $res = LogVisitModel::database()->delete(LogVisitModel::table(), "`id` IN ({$ids})");
            return $res ? $this->_apiSuccess($res) : $this->_apiFailed($res);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("删除访问记录异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function list(): BaseView
    {
        $login = $this->_checkAdmin();
        $search = $this->_request('search');
        $timeStart = $this->_request("timeStart");
        $timeEnd = $this->_request("timeEnd");
        $where = [];

        if (preg_match(self::PATTERN_TIME, $timeStart)) {
            $where[] = "`visit_time` >= '{$timeStart}'";
        }

        if (preg_match(self::PATTERN_TIME, $timeEnd)) {
            $where[] = "`visit_time` <= '{$timeEnd}'";
        }

        try {
            $pager = LogVisitModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                $search, join(' AND ', $where), [$this->_sort()]
            );
            return $this->_apiSuccess($pager);
        } catch (SqlException $e) {
            Mvc::getLogger()->error("访问记录列表异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }
}