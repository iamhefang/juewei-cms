<?php

namespace link\hefang\site\blog\controllers;
defined('PROJECT_NAME') or die("Access Refused");


use link\hefang\helpers\FileHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\entities\Pager;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class ThemeController extends BaseController
{
    public function list(): BaseView
    {
        $this->_checkAdmin();
        $themes = Mvc::getCache()->get('themes');
        $pageIndex = $this->_pageIndex();
        $pageSize = $this->_pageSize();
        $total = 0;
        $current = Mvc::getConfig('system|theme', Mvc::getDefaultTheme());
        if (Mvc::isDebug() || !is_array($themes) || count($themes) === 0) {
            $manifests = FileHelper::listFiles(PATH_THEMES, function ($file) {
                return StringHelper::endsWith($file, true, "manifest.json");
            });
            $themes = [];
            $start = ($pageIndex - 1) * $pageSize;
            $end = $start + $pageSize;
            $i = 0;
            foreach ($manifests as $manifest) {
                if ($i < $start || $i > $end) continue;
                $json = file_get_contents($manifest);
                $theme = json_decode($json, true);
                $theme['current'] = $theme['id'] === $current;
                $theme['author'] = array_merge([
                    'name' => null,
                    'email' => null,
                    'blog' => null
                ], isset($theme['author']) ? $theme['author'] : []);
                $themes[] = array_merge([
                    'configs' => [],
                    'keywords' => [],
                    'description' => null,
                    'cover' => null,
                    'url' => null
                ], $theme);
                $i++;
            }
            $total = count($manifests);
            Mvc::getCache()->set('themes', $themes);
        }
        $pager = new Pager($total, $pageIndex, $pageSize, $themes);
        return $this->_apiSuccess($pager);
    }
}