<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\models\BaseModel;


class ArticleModel extends BaseModel
{
    private $alias;
    private $title;
    private $keywords;
    private $description;
    private $post_time = 0;
    private $last_alter_time = 0;
    private $up_count = 0;
    private $read_count = 0;
    private $content;
    private $catalog;
    private $password;
    private $enable = true;
    private $cate_id;
    private $id;
    private $is_draft = false;
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
                    `content`=:content,
                    `password`=:password,
                    `is_draft`=:is_draft,
                    `covers`=:covers WHERE `id`=:id
SQL
                , [
                    'alias' => $m->getAlias(),
                    'title' => $m->getTitle(),
                    'keywords' => $m->getKeywords(),
                    'description' => $m->getDescription(),
                    'content' => $m->getContent(),
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

        return self::database()->transaction($sqls) > 0;
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
                    `content`,
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
                    :content,
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
                    'content' => $m->getContent(),
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
     * @return float
     */
    public function getPostTime(): float
    {
        return $this->post_time;
    }

    /**
     * @param float $post_time
     * @return ArticleModel
     */
    public function setPostTime(float $post_time): ArticleModel
    {
        $this->post_time = floor($post_time);
        return $this;
    }

    /**
     * @return int
     */
    public function getLastAlterTime(): int
    {
        return $this->last_alter_time;
    }

    /**
     * @param int $last_alter_time
     * @return ArticleModel
     */
    public function setLastAlterTime(int $last_alter_time): ArticleModel
    {
        $this->last_alter_time = $last_alter_time;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpCount(): int
    {
        return $this->up_count;
    }

    /**
     * @param int $up_count
     * @return ArticleModel
     */
    public function setUpCount(int $up_count): ArticleModel
    {
        $this->up_count = $up_count;
        return $this;
    }

    /**
     * @return int
     */
    public function getReadCount(): int
    {
        return $this->read_count;
    }

    /**
     * @param int $read_count
     * @return ArticleModel
     */
    public function setReadCount(int $read_count): ArticleModel
    {
        $this->read_count = $read_count;
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
     * @return ArticleModel
     */
    public function setContent($content): ArticleModel
    {
        $this->content = $content;
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
        return $this->cate_id;
    }

    /**
     * @param string $cate_id
     * @return ArticleModel
     */
    public function setCateId($cate_id): ArticleModel
    {
        $this->cate_id = $cate_id;
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
        return $this->is_draft;
    }

    /**
     * @param bool $is_draft
     * @return ArticleModel
     */
    public function setIsDraft(bool $is_draft): ArticleModel
    {
        $this->is_draft = $is_draft;
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
            'post_time' => 'post_time',
            'last_alter_time' => 'last_alter_time',
            'up_count' => 'up_count',
            'read_count' => 'read_count',
            'content' => 'content',
            'catalog' => 'catalog',
            'password' => 'password',
            'enable' => 'enable',
            'cate_id' => 'cate_id',
            'id' => 'id',
            'is_draft' => 'is_draft',
            'type' => 'type',
            'covers' => 'covers',
            'reprint_from' => 'reprintFrom'
        ];
    }
}