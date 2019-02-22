<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseModel;


class ViewTagModel extends BaseModel
{
    private $tag = '';
    private $contentCount = 0;
    private $type = 'article';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getContentCount(): int
    {
        return $this->contentCount;
    }

    public static function primaryKeyFields(): array
    {
        return ['tag', 'content_count'];
    }

    public static function readOnly(): bool
    {
        return true;
    }

    /**
     * 返回模型和数据库对应的字段
     * key 为数据库对应的字段名, value 为模型字段名
     * @return array
     */
    public static function fields(): array
    {
        return [
            'tag' => 'tag',
            'content_count' => 'contentCount',
            'type' => 'type'
        ];
    }
}