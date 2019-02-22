<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseModel;


class ViewArticleModel extends BaseModel
{
    private $alias = '';
    private $title = '';
    private $keywords = '';
    private $description = '';
    private $postTime = 0;
    private $lastAlterTime = 0;
    private $upCount = 0;
    private $readCount = 0;
    private $content = '';
    private $catalog = '';
    private $password = '';
    private $enable = true;
    private $cateId = '';
    private $id = '';
    private $cateName = '';
    private $cateAlias = '';
    private $tags = '';
    private $isDraft = false;
    private $type = 'article';
    private $covers = '[]';
    private $commentCount = 0;
    private $authorId = '';
    private $reprintFrom = null;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPostTime(): int
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $this->postTime)->getTimestamp();
    }

    /**
     * @return int
     */
    public function getLastAlterTime(): int
    {
        return $this->lastAlterTime;
    }

    /**
     * @return int
     */
    public function getUpCount(): int
    {
        return $this->upCount;
    }

    /**
     * @return int
     */
    public function getReadCount(): int
    {
        return $this->readCount;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @return string
     */
    public function getCateId()
    {
        return $this->cateId;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCateName()
    {
        return $this->cateName;
    }

    /**
     * @return string
     */
    public function getCateAlias()
    {
        return $this->cateAlias;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return explode(',', $this->tags) ?: [];
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getCovers(): array
    {
        return $this->covers ? json_decode($this->covers, true) : [];
    }

    /**
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * @return string
     */
    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getReprintFrom()
    {
        return $this->reprintFrom;
    }


    public function toMap(): array
    {
        $map = parent::toMap();
        $map['covers'] = $this->getCovers();
        $map['tags'] = $this->getTags();
        return $map;
    }

    public static function readOnly(): bool
    {
        return true;
    }

    public static function primaryKeyFields(): array
    {
        return ['id'];
    }

    public static function searchableFields(): array
    {
        return [
            'title', 'keywords', 'description', 'content', 'cate_name', 'tags'
        ];
    }

    public static function fields(): array
    {
        return [
            'alias' => 'alias',
            'title' => 'title',
            'keywords' => 'keywords',
            'description' => 'description',
            'post_time' => 'postTime',
            'last_alter_time' => 'lastAlterTime',
            'up_count' => 'upCount',
            'read_count' => 'readCount',
            'content' => 'content',
            'catalog' => 'catalog',
            'password' => 'password',
            'enable' => 'enable',
            'cate_id' => 'cateId',
            'id' => 'id',
            'cate_name' => 'cateName',
            'cate_alias' => 'cateAlias',
            'tags' => 'tags',
            'is_draft' => 'isDraft',
            'type' => 'type',
            'covers' => 'covers',
            'comment_count' => 'commentCount',
            'reprint_from' => 'reprintFrom'
        ];
    }
}