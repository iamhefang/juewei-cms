<?php

namespace link\hefang\site\admin\entities;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\interfaces\IJsonObject;
use link\hefang\interfaces\IMapObject;


class PollingEntity implements IJsonObject, IMapObject, \JsonSerializable
{
    private $unReadMessage = 0;
    /**
     * @var string|null
     */
    private $messageUrl = null;

    /**
     * @return int
     */
    public function getUnReadMessage(): int
    {
        return $this->unReadMessage;
    }

    /**
     * @param int $unReadMessage
     * @return PollingEntity
     */
    public function setUnReadMessage(int $unReadMessage): PollingEntity
    {
        $this->unReadMessage = $unReadMessage;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMessageUrl(): string
    {
        return $this->messageUrl;
    }

    /**
     * @param string $messageUrl
     * @return PollingEntity
     */
    public function setMessageUrl(string $messageUrl): PollingEntity
    {
        $this->messageUrl = $messageUrl;
        return $this;
    }


    public function toMap(): array
    {
        return [
            'unReadMessage' => $this->unReadMessage,
            'messageUrl' => $this->messageUrl
        ];
    }

    public function toJsonString(): string
    {
        return json_encode($this->toMap(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toMap();
    }
}