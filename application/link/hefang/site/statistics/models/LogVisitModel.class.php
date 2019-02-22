<?php

namespace link\hefang\site\statistics\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseModel;


class LogVisitModel extends BaseModel
{
    private $id = '';
    private $url = '';
    private $visitTime = '';
    private $userAgent = '';
    private $ip = '';
    private $referer = '';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return LogVisitModel
     */
    public function setId($id): LogVisitModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return LogVisitModel
     */
    public function setUrl($url): LogVisitModel
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getVisitTime()
    {
        return $this->visitTime;
    }

    /**
     * @param string $visitTime
     * @return LogVisitModel
     */
    public function setVisitTime($visitTime): LogVisitModel
    {
        $this->visitTime = $visitTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return LogVisitModel
     */
    public function setUserAgent($userAgent): LogVisitModel
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return LogVisitModel
     */
    public function setIp($ip): LogVisitModel
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     * @return LogVisitModel
     */
    public function setReferer($referer): LogVisitModel
    {
        $this->referer = $referer;
        return $this;
    }

    public static function primaryKeyFields(): array
    {
        return ['id'];
    }

    public static function searchableFields(): array
    {
        return ['ip', 'user_agent', 'url', 'referer'];
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
            'visit_time' => 'visitTime',
            'user_agent' => 'userAgent',
            'ip' => 'ip',
            'referer' => 'referer',
        ];
    }
}