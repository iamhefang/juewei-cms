<?php

namespace link\hefang\site\admin\models;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\helpers\ParseHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class ConfigModel extends BaseModel
{
    private $cate = '';
    private $key = '';
    private $value = '';
    private $type = '';
    private $name = '';
    private $canBeNull = true;
    private $nullTips = '';
    private $maxLength = 0;
    private $max = 0;
    private $min = 0;
    private $placeholder = '';
    private $isSecret = false;
    private $dependKey = '';
    private $attribute = "";
    private $enable = true;

    /**
     * @return string
     */
    public function getCate()
    {
        return $this->cate;
    }

    /**
     * @param string $cate
     * @return ConfigModel
     */
    public function setCate(string $cate): ConfigModel
    {
        $this->cate = $cate;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ConfigModel
     */
    public function setKey(string $key): ConfigModel
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return ConfigModel
     */
    public function setValue(string $value): ConfigModel
    {
        $this->value = $value;
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
     * @return ConfigModel
     */
    public function setType(string $type): ConfigModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ConfigModel
     */
    public function setName(string $name): ConfigModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanBeNull(): bool
    {
        return $this->canBeNull;
    }

    /**
     * @param bool $canBeNull
     * @return ConfigModel
     */
    public function setCanBeNull(bool $canBeNull): ConfigModel
    {
        $this->canBeNull = $canBeNull;
        return $this;
    }

    /**
     * @return string
     */
    public function getNullTips()
    {
        return $this->nullTips;
    }

    /**
     * @param string $nullTips
     * @return ConfigModel
     */
    public function setNullTips(string $nullTips): ConfigModel
    {
        $this->nullTips = $nullTips;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     * @return ConfigModel
     */
    public function setMaxLength(int $maxLength): ConfigModel
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     * @return ConfigModel
     */
    public function setMax(int $max): ConfigModel
    {
        $this->max = $max;
        return $this;
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     * @return ConfigModel
     */
    public function setMin(int $min): ConfigModel
    {
        $this->min = $min;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     * @return ConfigModel
     */
    public function setPlaceholder(string $placeholder): ConfigModel
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSecret(): bool
    {
        return !!$this->isSecret;
    }

    /**
     * @param bool $isSecret
     * @return ConfigModel
     */
    public function setIsSecret(bool $isSecret): ConfigModel
    {
        $this->isSecret = $isSecret;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttribute(): array
    {
        return $this->attribute ? json_decode($this->attribute, true) : [];
    }

    /**
     * @param array $attribute
     * @return ConfigModel
     */
    public function setAttribute(array $attribute): ConfigModel
    {
        $this->attribute = json_encode($attribute, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * @return string
     */
    public function getDependKey(): string
    {
        return $this->dependKey;
    }

    /**
     * @param string $dependKey
     * @return ConfigModel
     */
    public function setDependKey(string $dependKey): ConfigModel
    {
        $this->dependKey = $dependKey;
        return $this;
    }


    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable == '1';
    }

    /**
     * @param bool $enable
     * @return ConfigModel
     */
    public function setEnable(bool $enable): ConfigModel
    {
        $this->enable = $enable;
        return $this;
    }

    public static function needTrimFields(): array
    {
        return ['cate', 'key', 'type', 'null_tips', 'placeholder', 'depend_key'];
    }

    public static function searchableFields(): array
    {
        return ['name', 'null_tips', 'value', 'placeholder'];
    }

    public static function table(): string
    {
        return "system_config";
    }

    public static function primaryKeyFields(): array
    {
        return ['cate', 'key'];
    }

    public static function fields(): array
    {
        return [
            'cate' => 'cate',
            'key' => 'key',
            'value' => 'value',
            'type' => 'type',
            'name' => 'name',
            'can_be_null' => 'canBeNull',
            'null_tips' => 'nullTips',
            'max_length' => 'maxLength',
            'max' => 'max',
            'min' => 'min',
            'placeholder' => 'placeholder',
            'is_secret' => 'isSecret',
            'depend_key' => 'dependKey',
            'enable' => 'enable',
            'attribute' => 'attribute'
        ];
    }

    public function toMap(): array
    {
        $map = parent::toMap();
        $map['attribute'] = $this->getAttribute();
        return $map;
    }

    public static function all(bool $force = false): array
    {
        $cache = Mvc::getCache()->get("all_enabled_config");
        if ($force || !is_array($cache) || count($cache) < 1 || Mvc::isDebug()) {
            try {
                $cache = [];
                $pager = ConfigModel::pager(
                    1,
                    500,
                    null,
                    "enable = TRUE",
                    [new SqlSort('sort', SqlSort::TYPE_ASC)]
                );
                foreach ($pager->getData() as $item) {
                    if (!($item instanceof ConfigModel) || !$item->isExist()) continue;
                    $key = $item->getCate() . '|' . $item->getKey();
                    switch (strtolower($item->getType())) {
                        case 'int':
                        case 'float':
                            $cache[$key] = +$item->getValue();
                            break;
                        case 'boolean':
                        case 'bool':
                            $cache[$key] = ParseHelper::parseBoolean($item->getValue(), true);
                            break;
                        default:
                            $cache[$key] = $item->getValue();
                    }
                }
                Mvc::getCache()->set("all_enabled_config", $cache);
            } catch (\Throwable $e) {
                Mvc::getLogger()->error("全部系统配置异常", $e->getMessage(), $e);
                $cache = [];
            }
        }
        return $cache;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \link\hefang\mvc\exceptions\SqlException
     */
    public static function alter(array $data): bool
    {
        $db = self::database();
        $sqls = [];
        foreach ($data as $item => $value) {
            list($cate, $key) = explode('|', $item);
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $sqls[] = new Sql(
                "UPDATE `system_config` SET `value` = :value WHERE `cate` = :cate AND `key` = :key",
                ['value' => $value, 'cate' => $cate, 'key' => $key]
            );
        }

        return $db->transaction($sqls) > 0;
    }
}