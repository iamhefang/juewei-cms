<?php

namespace link\hefang\site\users\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseLoginModel;

class LoginModel extends BaseLoginModel
{
    const ID_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const UNLOCK_MAX_TRY = 5;

    private $id = '';
    private $roleId = '';
    private $loginName = '';
    private $nickName = '';
    private $phone = '';
    private $email = '';
    private $idCard = '';
    private $registerTime = 0;
    private $registerType = '';
    private $lastLoginTime = 0;
    private $lastLoginIp = '';
    private $password = '';
    private $enable = true;
    private $headImgUrl = null;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return LoginModel
     */
    public function setId(string $id): LoginModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param string $roleId
     * @return LoginModel
     */
    public function setRoleId(string $roleId): LoginModel
    {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginName()
    {
        return $this->loginName;
    }

    /**
     * @param string $loginName
     * @return LoginModel
     */
    public function setLoginName(string $loginName): LoginModel
    {
        $this->loginName = $loginName;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @param string $nickName
     * @return LoginModel
     */
    public function setNickName(string $nickName): LoginModel
    {
        $this->nickName = $nickName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return LoginModel
     */
    public function setPhone(string $phone): LoginModel
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return LoginModel
     */
    public function setEmail(string $email): LoginModel
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param string $idCard
     * @return LoginModel
     */
    public function setIdCard(string $idCard): LoginModel
    {
        $this->idCard = $idCard;
        return $this;
    }

    /**
     * @return int
     */
    public function getRegisterTime(): int
    {
        return $this->registerTime;
    }

    /**
     * @param int $register_time
     * @return LoginModel
     */
    public function setRegisterTime(int $register_time): LoginModel
    {
        $this->registerTime = $register_time;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterType()
    {
        return $this->registerType;
    }

    /**
     * @param string $registerType
     * @return LoginModel
     */
    public function setRegisterType(string $registerType): LoginModel
    {
        $this->registerType = $registerType;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastLoginTime(): int
    {
        return $this->lastLoginTime;
    }

    /**
     * @param int $lastLoginTime
     * @return LoginModel
     */
    public function setLastLoginTime(int $lastLoginTime): LoginModel
    {
        $this->lastLoginTime = $lastLoginTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastLoginIp()
    {
        return $this->lastLoginIp;
    }

    /**
     * @param string $lastLoginIp
     * @return LoginModel
     */
    public function setLastLoginIp(string $lastLoginIp): LoginModel
    {
        $this->lastLoginIp = $lastLoginIp;
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
     * @return LoginModel
     */
    public function setPassword(string $password): LoginModel
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
     * @return LoginModel
     */
    public function setEnable(bool $enable): LoginModel
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return null
     */
    public function getHeadImgUrl()
    {
        return $this->headImgUrl;
    }

    /**
     * @param null $headImgUrl
     * @return LoginModel
     */
    public function setHeadImgUrl($headImgUrl)
    {
        $this->headImgUrl = $headImgUrl;
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
            'role_id' => 'roleId',
            'login_name' => 'loginName',
            'nick_name' => 'nickName',
            'phone' => 'phone',
            'email' => 'email',
            'id_card' => 'idCard',
            'register_time' => 'registerTime',
            'register_type' => 'registerType',
            'last_login_time' => 'lastLoginTime',
            'last_login_ip' => 'lastLoginIp',
            'password' => 'password',
            'enable' => 'enable',
            'head_img_url' => 'headImgUrl'
        ];
    }

    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->getRoleId() === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->getId() === self::ID_ROOT;
    }

    /**
     * @return string|null
     */
    public function getRoleName()
    {
        $name = '';

        if ($this->isSuperAdmin()) {
            $name = '超级管理员';
        } else if ($this->isAdmin()) {
            $name = '管理员';
        }

        if ($this->isDeveloper()) {
            $name .= '[开发者]';
        }
        return $name;
    }
}