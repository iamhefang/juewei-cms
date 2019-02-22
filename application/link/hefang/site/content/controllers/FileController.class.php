<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\FileModel;


class FileController extends BaseController
{
    public function list(): BaseView
    {
        $login = $this->_checkLogin();
        $where = "`enable` = TRUE";
        $loginId = $this->_request("loginId");
        $search = $this->_request("search");
        $mimeType = $this->_request("mimeType");
        try {
            if (!StringHelper::isNullOrBlank($mimeType)) {
                $mimeType = addslashes($mimeType);
                $where .= " AND `mime_type` = '{$mimeType}'";
            }
            if ($login->isAdmin()) {
                if (strlen($loginId) === 40) {
                    $loginId = addslashes($loginId);
                    $where .= " AND `login_id` = '{$loginId}'";
                }
            } else {
                $where .= " AND `login_id` = '{$login->getId()}'";
            }
            $pager = FileModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                $search,
                $where,
                [$this->_sort()]
            );
            return $this->_apiSuccess($pager);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("文件列表异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }
}