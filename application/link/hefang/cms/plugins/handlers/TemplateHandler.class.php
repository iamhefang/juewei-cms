<?php

namespace link\hefang\cms\plugins\handlers;

use link\hefang\helpers\CollectionHelper;

defined('PHP_MVC') or die('Access Refused');


/**
 * Class TemplateHandler
 * @method beforeHead(string $html):TemplateHandler
 * @method afterHead(string $html):TemplateHandler
 * @method afterBody(string $html):TemplateHandler
 * @method beforeBody(string $html):TemplateHandler
 * @package link\hefang\cms\plugins\handlers
 */
class TemplateHandler
{
    private $html = [];

    public function __call($name, $arguments)
    {
        $this->html[$name] = $arguments[0];
        return $this;
    }

    public function get(string $name)
    {
        return CollectionHelper::getOrDefault($this->html, $name, '');
    }
}