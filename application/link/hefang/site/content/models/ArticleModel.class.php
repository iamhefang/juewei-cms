<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;


class ArticleModel extends BaseModel
{
    private $alias;
    private $title;
    private $keywords;
    private $description;
    private $postTime = 0;
    private $lastAlterTime = 0;
    private $upCount = 0;
    private $readCount = 0;
    private $html;
    private $markdown;
    private $catalog;
    private $password;
    private $enable = true;
    private $cateId;
    private $id;
    private $isDraft = false;
    private $type = 'article';
    private $covers = '[]';
    private $authorId;
    private $reprintFrom;

    /**
     * @param ArticleModel $m
     * @param array $tags
     * @return bool
     * @throws \link\hefang\mvc\exceptions\SqlException
     */
    public static function alter(ArticleModel $m, array $tags)
    {
        $sqls = [
            new Sql("DELETE FROM `tag` WHERE `content_id`=:id", ['id' => $m->getId()]),
            new Sql(
                <<<SQL
                UPDATE `article` SET 
                    `alias`=:alias,
                    `title`=:title,
                    `keywords`=:keywords,
                    `description`=:description,
                    `html`=:html,
                    `markdown`=:markdown,
                    `password`=:password,
                    `is_draft`=:is_draft,
                    `covers`=:covers WHERE `id`=:id
SQL
                , [
                    'alias' => $m->getAlias(),
                    'title' => $m->getTitle(),
                    'keywords' => $m->getKeywords(),
                    'description' => $m->getDescription(),
                    'html' => $m->getHtml(),
                    'markdown' => $m->getMarkdown(),
                    'password' => $m->getPassword(),
                    'id' => $m->getId(),
                    'is_draft' => $m->isDraft(),
                    'covers' => json_encode($m->getCovers(), JSON_UNESCAPED_UNICODE)
                ]
            )
        ];
        if ($m->getType() === 'article') {
            foreach ($tags as $tag) {
                $sqls[] = new Sql(
                    "INSERT INTO `tag`(`tag`,content_id) VALUES (:tag,:article_id)",
                    [
                        'tag' => $tag,
                        'article_id' => $m->getId()
                    ]
                );
            }
        }

        $res = self::database()->transaction($sqls) > 0;
        if ($res) {
            Mvc::getCache()->remove($m->getAlias());
            Mvc::getCache()->remove($m->getId());
        }
        return $res;
    }

    /**
     * @param ArticleModel $m
     * @param array|null $tags
     * @return bool
     * @throws \link\hefang\mvc\exceptions\SqlException
     */
    public static function add(ArticleModel $m, array $tags = null)
    {
        $sqls = [
            new Sql(
                <<<SQL
                INSERT  INTO `article`(
                    `alias`,
                    `title`,
                    `keywords`,
                    `description`,
                    `post_time`,
                    `html`,
                    `markdown`,
                    `password`,
                    `id`,
                    `is_draft`,
                    `type`,
                    `covers`,
                    `author_id`
                ) VALUES (
                    :alias,
                    :title,
                    :keywords,
                    :description,
                    :post_time,
                    :html,
                    :markdown,
                    :password,
                    :id,
                    :is_draft,
                    :type,
                    :covers,
                    :author_id
                )
SQL
                , [
                    'alias' => $m->getAlias(),
                    'title' => $m->getTitle(),
                    'keywords' => $m->getKeywords(),
                    'description' => $m->getDescription(),
                    'post_time' => TimeHelper::formatMillis("Y-m-d H:i:s", $m->getPostTime()),
                    'html' => $m->getHtml(),
                    'markdown' => $m->getMarkdown(),
                    'password' => $m->getPassword(),
                    'id' => $m->getId(),
                    'is_draft' => $m->isDraft(),
                    'type' => $m->getType(),
                    'covers' => json_encode($m->getCovers(), JSON_UNESCAPED_UNICODE),
                    'author_id' => $m->getAuthorId(),
                ]
            )
        ];
        if ($m->getType() === 'article') {
            foreach ($tags as $tag) {
                $sqls[] = new Sql(
                    "INSERT INTO `tag`(`tag`,content_id) VALUES (:tag,:article_id)",
                    [
                        'tag' => $tag,
                        'article_id' => $m->getId()
                    ]
                );
            }
        }

        return self::database()->transaction($sqls) > 0;
    }

    public static function up(string $id)
    {
        try {
            self::database()->executeUpdate(new Sql(
                "UPDATE `article` SET `up_count` = `up_count` + 1 WHERE `id` = :id",
                ['id' => $id]
            ));
        } catch (SqlException $e) {
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return ArticleModel
     */
    public function setAlias($alias): ArticleModel
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ArticleModel
     */
    public function setTitle($title): ArticleModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     * @return ArticleModel
     */
    public function setKeywords($keywords): ArticleModel
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ArticleModel
     */
    public function setDescription($description): ArticleModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostTime(): string
    {
        return $this->postTime;
    }

    /**
     * @param float $postTime
     * @return ArticleModel
     */
    public function setPostTime(float $postTime): ArticleModel
    {
        $this->postTime = floor($postTime);
        return $this;
    }

    /**
     * @return string
     */
    public function getLastAlterTime(): string
    {
        return $this->lastAlterTime;
    }

    /**
     * @param float $lastAlterTime
     * @return ArticleModel
     */
    public function setLastAlterTime(float $lastAlterTime): ArticleModel
    {
        $this->lastAlterTime = floor($lastAlterTime);
        return $this;
    }

    /**
     * @return int
     */
    public function getUpCount(): int
    {
        return $this->upCount;
    }

    /**
     * @param int $upCount
     * @return ArticleModel
     */
    public function setUpCount(int $upCount): ArticleModel
    {
        $this->upCount = $upCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getReadCount(): int
    {
        return $this->readCount;
    }

    /**
     * @param int $readCount
     * @return ArticleModel
     */
    public function setReadCount(int $readCount): ArticleModel
    {
        $this->readCount = $readCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     * @return ArticleModel
     */
    public function setHtml($html): ArticleModel
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMarkdown()
    {
        return $this->markdown;
    }

    /**
     * @param string $markdown
     * @return ArticleModel
     */
    public function setMarkdown($markdown)
    {
        $this->markdown = $markdown;
        return $this;
    }

    /**
     * @return string
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * @param string $catalog
     * @return ArticleModel
     */
    public function setCatalog($catalog): ArticleModel
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return ArticleModel
     */
    public function setPassword($password): ArticleModel
    {
        $this->password = $password;
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
     * @return ArticleModel
     */
    public function setEnable(bool $enable): ArticleModel
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return string
     */
    public function getCateId()
    {
        return $this->cateId;
    }

    /**
     * @param string $cateId
     * @return ArticleModel
     */
    public function setCateId($cateId): ArticleModel
    {
        $this->cateId = $cateId;
        return $this;
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
     * @return ArticleModel
     */
    public function setId($id): ArticleModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    /**
     * @param bool $isDraft
     * @return ArticleModel
     */
    public function setIsDraft(bool $isDraft): ArticleModel
    {
        $this->isDraft = $isDraft;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ArticleModel
     */
    public function setType($type): ArticleModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getCovers(): array
    {
        return $this->covers ? json_decode($this->covers, true) : [];
    }

    /**
     * @param array $covers
     * @return ArticleModel
     */
    public function setCovers(array $covers): ArticleModel
    {
        $this->covers = json_encode($covers, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param string $authorId
     * @return ArticleModel
     */
    public function setAuthorId($authorId): ArticleModel
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return string
     */
    public function getReprintFrom()
    {
        return $this->reprintFrom;
    }

    /**
     * @param string $reprintFrom
     * @return ArticleModel
     */
    public function setReprintFrom($reprintFrom)
    {
        $this->reprintFrom = $reprintFrom;
        return $this;
    }

    public static function primaryKeyFields(): array
    {
        return ['id'];
    }

    public static function bigDataFields(): array
    {
        return ['markdown', 'html'];
    }

    /**
     * 返回模型和数据库对应的字段
     * key 为数据库对应的字段名, value 为模型字段名
     * @return array
     */
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
            'html' => 'html',
            'markdown' => 'markdown',
            'catalog' => 'catalog',
            'password' => 'password',
            'enable' => 'enable',
            'cate_id' => 'cateId',
            'id' => 'id',
            'is_draft' => 'isDraft',
            'type' => 'type',
            'covers' => 'covers',
            'reprint_from' => 'reprintFrom'
        ];
    }
}