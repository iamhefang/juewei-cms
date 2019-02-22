<?php

namespace link\hefang\site\ueditor\models;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\mvc\Mvc;

class UEditorConfig
{
    public static function get(): array
    {
        $config = Mvc::getCache()->get("ueditor.config");
        if (!is_array($config)) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . "ueditor.config.json";
            if (!file_exists($file)) {
                return [];
            }
            $json = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "ueditor.config.json");
            $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $json), true);
            Mvc::getCache()->set('ueditor.config', $config);
        }
        return $config;
    }
}