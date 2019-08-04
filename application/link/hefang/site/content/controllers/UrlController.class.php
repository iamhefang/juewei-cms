<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\UrlModel;


class UrlController extends BaseController
{
	public function list(): BaseView
	{
		$this->_checkAdmin();
		try {
			$pager = UrlModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				null, "`enable` = TRUE",
				[$this->_sort()]
			);
			return $this->_apiSuccess($pager);
		} catch (\Throwable $e) {
			Mvc::getLogger()->error("短连接列表异常", $e->getMessage(), $e);
			return $this->_apiFailed($e->getMessage());
		}
	}

	public function insert(): BaseView
	{
		$this->_checkAdmin();
		$disposable = ParseHelper::parseBoolean($this->_post("disposable", "true"), true);
		$expiresIn = $this->_post("expiresIn");
		$url = $this->_post("url");

		if (StringHelper::isNullOrBlank($url)) {
			return $this->_apiFailed("长链接不能为空");
		}

		try {
			$model = new UrlModel();
			$model->setId(RandomHelper::letter(rand(10, 30)))
				->setDisposable($disposable)
				->setUrl($url)
				->setEnable(true);
			if (is_numeric($expiresIn)) {
				$model->setExpiresIn(+$expiresIn);
			}
			$res = $model->insert();
			return $res ? $this->_apiSuccess($model) : $this->_apiFailed("添加短链接失败");
		} catch (\Exception $e) {
			return $this->_exception($e);
		}
	}

	public function delete(): BaseView
	{
		$this->_checkAdmin();
		$id = $this->_post("id");
		if (!is_array($id)) return $this->_apiFailed('参数异常');
		$ids = "'" . join("','", $id) . "'";
		$where = "`id` IN ({$ids})";
		try {
			$res = UrlModel::database()->delete(UrlModel::table(), $where);
			return $res ? $this->_apiSuccess($res) : $this->_apiFailed($res);
		} catch (\Throwable $e) {
			Mvc::getLogger()->error("删除短链接异常", $e->getMessage(), $e);
			return $this->_apiFailed($e->getMessage());
		}
	}

	public function visit(string $id): BaseView
	{
		$id = $this->_request("id", $id);
		if (StringHelper::isNullOrBlank($id)) {
			return $this->_text("参数异常");
		}
		try {
			$m = UrlModel::get($id);
			if (!($m instanceof UrlModel) || !$m->isExist() || !$m->isEnable()) {
				return $this->_text("该连接不存在或已失效");
			}
			if ($m->getExpiresIn() != null && $m->getExpiresIn() < time()) {
				$m->setDisposable(true)->visit();
				return $this->_text("该连接已过期");
			}
			$m->visit();
			return $this->_redirect($m->getUrl());
		} catch (\Throwable $e) {
			Mvc::getLogger()->error("短连接访问异常", $e->getMessage(), $e);
			return $this->_apiFailed($e->getMessage());
		}
	}
}
