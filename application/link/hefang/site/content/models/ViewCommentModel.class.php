<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\mvc\models\BaseModel;

class ViewCommentModel extends BaseModel
{
    private $id = '';
    private $contentId = '';
    private $comment = '';
    private $postTime = null;
    private $authorInfo = '';
    private $enable = true;
    private $floor = 0;
    private $replayId = '';
    private $readTime;
    private $checked = true;
    private $contentTitle = '';
    private $replayFloor = null;
    private $contentAlias = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContentId(): string
    {
        return $this->contentId;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return null
     */
    public function getPostTime()
    {
        return $this->postTime;
    }

    /**
     * @return array
     */
    public function getAuthorInfo()
    {
        return $this->authorInfo ? json_decode($this->authorInfo, true) : [];
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @return string
     */
    public function getReplayId(): string
    {
        return $this->replayId;
    }

    /**
     * @return mixed
     */
    public function getReadTime()
    {
        return $this->readTime;
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @return string
     */
    public function getContentTitle(): string
    {
        return $this->contentTitle;
    }

    /**
     * @return null|int
     */
    public function getReplayFloor()
    {
        return $this->replayFloor;
    }

    /**
     * @return null|string
     */
    public function getContentAlias()
    {
        return $this->contentAlias;
    }

    public function toMap(): array
    {
        $map = parent::toMap();
        $map['authorInfo'] = $this->getAuthorInfo();
        return $map;
    }

    public static function primaryKeyFields(): array
    {
        return [];
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
            'id' => 'id',
            'content_id' => 'contentId',
            'comment' => 'comment',
            'post_time' => 'postTime',
            'author_info' => 'authorInfo',
            'enable' => 'enable',
            'floor' => 'floor',
            'replay_id' => 'replayId',
            'read_time' => 'readTime',
            'content_title' => 'contentTitle',
            'replay_floor' => 'replayFloor',
            'content_alias' => 'contentAlias'
        ];
    }
}