<?php

namespace link\hefang\site\admin\models;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\helpers\StringHelper;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\models\BaseLoginModel;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class FunctionModel extends BaseModel
{
    private $id = '';
    private $parentId = '';
    private $label = '';
    private $icon = '';
    private $target = '';
    private $link = '';
    private $sort = 0;
    private $enable = true;

    public $child = [];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return FunctionModel
     */
    public function setId(string $id): FunctionModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return FunctionModel
     */
    public function setParentId(string $parentId): FunctionModel
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return FunctionModel
     */
    public function setLabel(string $label): FunctionModel
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return FunctionModel
     */
    public function setIcon(string $icon): FunctionModel
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return FunctionModel
     */
    public function setTarget(string $target): FunctionModel
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return FunctionModel
     */
    public function setLink(string $link): FunctionModel
    {
        $this->link = $link;
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
     * @return FunctionModel
     */
    public function setSort(int $sort): FunctionModel
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
     * @return FunctionModel
     */
    public function setEnable(bool $enable): FunctionModel
    {
        $this->enable = $enable;
        return $this;
    }

    public function toMap(): array
    {
        $map = parent::toMap();
        $map['child'] = $this->child;
        return $map;
    }

    public static function byLogin(BaseLoginModel $login): array
    {
        try {
            if (!$login->isAdmin()) return [];
            $where = "enable = TRUE";
            $root = [];
            $child = [];
            $pager = FunctionModel::pager(
                1,
                100,
                null,
                $where,
                [new SqlSort('sort', SqlSort::TYPE_ASC)]
            );

            foreach ($pager->getData() as $item) {
                if (!($item instanceof FunctionModel) || !$item->isExist()) continue;
                if (!StringHelper::isNullOrBlank($item->getParentId())) {
                    $child[] = $item;
                } else {
                    $root[$item->getId()] = $item;
                }
            }

            foreach ($child as $item) {
                if (array_key_exists($item->getParentId(), $root)) {
                    $root[$item->getParentId()]->child[] = $item;
                }
            }

            return array_values($root);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("功能列表异常", $e->getMessage(), $e);
            return [];
        }
    }

    public static function needTrimFields(): array
    {
        return ['id', 'parent_id', 'label', 'target', 'link'];
    }

    public static function table(): string
    {
        return 'function';
    }

    public static function primaryKeyFields(): array
    {
        return ['id'];
    }

    public static function fields(): array
    {
        return [
            'id' => 'id',
            'parent_id' => 'parentId',
            'label' => 'label',
            'icon' => 'icon',
            'target' => 'target',
            'link' => 'link',
            'sort' => 'sort',
            'enable' => 'enable',
        ];
    }
}