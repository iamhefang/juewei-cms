<?php

namespace link\hefang\site\content\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\models\BaseModel;


class FileModel extends BaseModel
{
    private $id = '';
    private $savePath = '';
    private $fileName = '';
    private $size = 0;
    private $uploadTime = 0;
    private $loginId = '';
    private $uploadFrom = '';
    private $mimeType = '';
    private $isSecret = false;
    private $enable = true;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return FileModel
     */
    public function setId($id): FileModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * @param string $savePath
     * @return FileModel
     */
    public function setSavePath($savePath): FileModel
    {
        $this->savePath = $savePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return FileModel
     */
    public function setFileName($fileName): FileModel
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return FileModel
     */
    public function setSize(int $size): FileModel
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getUploadTime(): int
    {
        return $this->uploadTime;
    }

    /**
     * @param int $uploadTime
     * @return FileModel
     */
    public function setUploadTime(int $uploadTime): FileModel
    {
        $this->uploadTime = $uploadTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     * @param string $loginId
     * @return FileModel
     */
    public function setLoginId($loginId): FileModel
    {
        $this->loginId = $loginId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadFrom()
    {
        return $this->uploadFrom;
    }

    /**
     * @param string $uploadFrom
     * @return FileModel
     */
    public function setUploadFrom($uploadFrom): FileModel
    {
        $this->uploadFrom = $uploadFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return FileModel
     */
    public function setMimeType($mimeType): FileModel
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSecret(): bool
    {
        return $this->isSecret;
    }

    /**
     * @param bool $isSecret
     * @return FileModel
     */
    public function setIsSecret(bool $isSecret): FileModel
    {
        $this->isSecret = $isSecret;
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
     * @return FileModel
     */
    public function setEnable(bool $enable): FileModel
    {
        $this->enable = $enable;
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
            'save_path' => 'savePath',
            'file_name' => 'fileName',
            'size' => 'size',
            'upload_time' => 'uploadTime',
            'login_id' => 'loginId',
            'upload_from' => 'uploadFrom',
            'mime_type' => 'mimeType',
            'is_secret' => 'isSecret',
            'enable' => 'enable',
        ];
    }
}