<?php

namespace link\hefang\site\statistics\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;


class MainController extends BaseController
{
    public function article(): BaseView
    {
        $this->_checkAdmin();

        try {
            $articleStatistics = Mvc::getDatabase()->executeQuery(new Sql(<<<SQL
            SELECT
              (SELECT count(*)
               FROM article
               WHERE type = 'article')                                       AS article_count,
              (SELECT count(*)
               FROM article
               WHERE type = 'article' AND enable = FALSE)                    AS article_not_enable_count,
              (SELECT count(*)
               FROM article
               WHERE type = 'article' AND is_draft = TRUE AND enable = TRUE) AS article_draft_count,
              (SELECT count(*)
               FROM article
               WHERE type = 'page')                                          AS page_count,
              (SELECT count(*)
               FROM article
               WHERE type = 'page' AND enable = FALSE)                       AS page_not_enable_count,
              (SELECT count(*)
               FROM article
               WHERE type = 'page' AND is_draft = TRUE AND enable = TRUE)    AS page_draft_count
SQL
            ));
            $res = [];
            foreach ($articleStatistics[0] as $key => $val) {
                $res[StringHelper::underLine2hump($key, false)] = +$val;
            }
            return $this->_apiSuccess($res);
        } catch (SqlException $e) {
        }
        return $this->_null();
    }

    public function comment(): BaseView
    {
        $this->_checkAdmin();
    }
}