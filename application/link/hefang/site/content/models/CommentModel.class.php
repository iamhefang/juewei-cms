<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\models\BaseModel;


class CommentModel extends BaseModel
{
    private $id = '';
    private $contentId = '';
    private $comment = '';
    private $postTime = 0;
    private $authorInfo = '';
    private $enable = true;
    private $floor = 0;
    private $replayId = '';
    private $readTime;
    private $checked = true;

    /**
     * @param CommentModel $m
     * @return bool
     * @throws \link\hefang\mvc\exceptions\SqlException
     */
    public static function add(CommentModel $m)
    {
        $floor = self::database()->single(self::table(),
            'IFNULL(max(`floor`), 0) + 1',
            "`content_id` = '{$m->getContentId()}'"
        );
        $m->setFloor($floor);
        return self::database()->executeUpdate(new Sql(
                "INSERT INTO `comment`(
                    id, 
                    content_id, 
                    `comment`,
                    author_info, 
                    replay_id,
                    post_time,
                    floor
                ) VALUES (
                  :id,
                  :content_id,
                  :comment,
                  :author_info,
                  :replay_id,
                  current_timestamp(),
                  :floor
                );",
                [
                    'id' => $m->getId(),
                    'content_id' => $m->getContentId(),
                    'comment' => $m->getComment(),
                    'author_info' => json_encode($m->getAuthorInfo(), JSON_UNESCAPED_UNICODE),
                    'replay_id' => $m->getReplayId(),
                    'floor' => $m->getFloor()
                ]
            )) > 0;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CommentModel
     */
    public function setId(string $id): CommentModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param string $contentId
     * @return CommentModel
     */
    public function setContentId(string $contentId): CommentModel
    {
        $this->contentId = $contentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return CommentModel
     */
    public function setComment(string $comment): CommentModel
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostTime(): int
    {
        return $this->postTime;
    }

    /**
     * @param int $postTime
     * @return CommentModel
     */
    public function setPostTime(int $postTime): CommentModel
    {
        $this->postTime = $postTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getAuthorInfo()
    {
        return $this->authorInfo ? json_decode($this->authorInfo, true) : [];
    }

    /**
     * @param array $authorInfo
     * @return CommentModel
     */
    public function setAuthorInfo(array $authorInfo): CommentModel
    {
        $this->authorInfo = json_encode($authorInfo, JSON_UNESCAPED_UNICODE);
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
     * @return CommentModel
     */
    public function setEnable(bool $enable): CommentModel
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     * @return CommentModel
     */
    public function setFloor(int $floor): CommentModel
    {
        $this->floor = $floor;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplayId()
    {
        return $this->replayId;
    }

    /**
     * @param string $replayId
     * @return CommentModel
     */
    public function setReplayId($replayId): CommentModel
    {
        $this->replayId = $replayId;
        return $this;
    }

    /**
     * @return string
     */
    public function getReadTime()
    {
        return $this->readTime;
    }

    /**
     * @param string $readTime
     * @return CommentModel
     */
    public function setReadTime($readTime)
    {
        $this->readTime = $readTime;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     * @return CommentModel
     */
    public function setChecked(bool $checked): CommentModel
    {
        $this->checked = $checked;
        return $this;
    }

    public function toMap(): array
    {
        $map = parent::toMap();
        $map['authorInfo'] = $this->getAuthorInfo();
        return $map;
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
            'content_id' => 'contentId',
            'comment' => 'comment',
            'post_time' => 'postTime',
            'author_info' => 'authorInfo',
            'enable' => 'enable',
            'floor' => 'floor',
            'replay_id' => 'replayId',
            'read_time' => 'readTime'
        ];
    }
}