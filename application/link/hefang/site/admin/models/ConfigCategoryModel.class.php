<?php
/**
 * Created by IntelliJ IDEA.
 * User: hefang
 * Date: 2018/12/22
 * Time: 14:57
 */

namespace link\hefang\site\admin\models;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\mvc\models\BaseModel;

class ConfigCategoryModel extends BaseModel
{

    private $id = '';
    private $name = '';
    private $description = '';
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
     * @return ConfigCategoryModel
     */
    public function setId(string $id): ConfigCategoryModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ConfigCategoryModel
     */
    public function setName(string $name): ConfigCategoryModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ConfigCategoryModel
     */
    public function setDescription(string $description): ConfigCategoryModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return !!$this->enable;
    }

    /**
     * @param bool $enable
     * @return ConfigCategoryModel
     */
    public function setEnable(bool $enable): ConfigCategoryModel
    {
        $this->enable = $enable;
        return $this;
    }

    public static function searchableFields(): array
    {
        return ['name', 'description'];
    }

    public static function needTrimFields(): array
    {
        return ['name', 'id', 'description'];
    }

    public static function table(): string
    {
        return "config_category";
    }

    public static function primaryKeyFields(): array
    {
        return ['id'];
    }

    public static function fields(): array
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'description' => 'description',
            'enable' => 'enable'
        ];
    }
}