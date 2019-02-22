<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseModel;

class SwiperModel extends BaseModel
{
    private $id = '';
    private $content = '';
    private $sort = 0;
    private $enable = true;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return SwiperModel
     */
    public function setId(string $id): SwiperModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return SwiperModel
     */
    public function setContent($content): SwiperModel
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return SwiperModel
     */
    public function setSort(int $sort): SwiperModel
    {
        $this->sort = $sort;
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
     * @return SwiperModel
     */
    public function setEnable(bool $enable): SwiperModel
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
            'content' => 'content',
            'sort' => 'sort',
            'enable' => 'enable'
        ];
    }
}