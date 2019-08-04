<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;


class UrlModel extends BaseModel
{
	private $id = '';
	private $url = '';
	private $expiresIn = null;
	private $visitCount = 0;
	private $disposable = true;
	private $enable = true;

	public function visit()
	{
		try {
			if ($this->isDisposable()) {
				self::database()->delete(self::table(), "`id` = '{$this->getId()}'");
			} else {
				self::database()->executeUpdate(new Sql(
					"UPDATE `url` SET `visit_count` = `visit_count` + 1 WHERE `id`= :id", [
						'id' => $this->getId()
					]
				));
			}
		} catch (SqlException $e) {
		}
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return UrlModel
	 */
	public function setId(string $id): UrlModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return UrlModel
	 */
	public function setUrl(string $url): UrlModel
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExpiresIn()
	{
		return $this->expiresIn;
	}

	/**
	 * @param int $expiresIn
	 * @return UrlModel
	 */
	public function setExpiresIn(int $expiresIn): UrlModel
	{
		$this->expiresIn = $expiresIn;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVisitCount(): int
	{
		return $this->visitCount;
	}

	/**
	 * @param int $visitCount
	 * @return UrlModel
	 */
	public function setVisitCount(int $visitCount): UrlModel
	{
		$this->visitCount = $visitCount;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDisposable(): bool
	{
		return $this->disposable;
	}

	/**
	 * @param bool $disposable
	 * @return UrlModel
	 */
	public function setDisposable(bool $disposable): UrlModel
	{
		$this->disposable = $disposable;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEnable(): bool
	{
		return $this->enable;
	}

	/**
	 * @param bool $enable
	 * @return UrlModel
	 */
	public function setEnable(bool $enable): UrlModel
	{
		$this->enable = $enable;
		return $this;
	}


	public static function primaryKeyFields(): array
	{
		return ['id'];
	}

	/**
	 * 返回模型和数据库对应的字段
	 * key 为数据库对应的字段名, value 为模型字段名
	 * @return array
	 */
	public static function fields(): array
	{
		return [
			'id' => 'id',
			'url' => 'url',
			'expires_in' => 'expiresIn',
			'visit_count' => 'visitCount',
			'disposable' => 'disposable',
			'enable' => 'enable',
		];
	}
}
