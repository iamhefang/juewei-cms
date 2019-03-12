<?php

namespace link\hefang\site\blog\controllers;


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\mvc\views\TextView;
use link\hefang\site\content\models\ArticleModel;

class RssController extends BaseController
{
    public function index(): BaseView
    {
        try {
            $pager = ArticleModel::pager(
                1,
                20,
                null,
                "`enable` = true AND `type` = 'article' AND `is_draft` = false"
            );
            return $this->_text(
                $this->makeXml(
                    Mvc::getConfig('site|name', PROJECT_NAME),
                    Mvc::getConfig('site|description', PROJECT_NAME),
                    'https://hefang.link',
                    'utf-8', $pager->getData()
                ),
                TextView::XML
            );
        } catch (SqlException $e) {
            return $this->_exception($e);
        }
    }

    public function tag(string $tag): BaseView
    {
        try {
            $pager = ArticleModel::pager(
                1,
                20,
                null,
                new Sql(
                    "`enable` = true AND `type` = 'article' AND `is_draft` = false AND `id` IN (SELECT `content_id` FROM `tag` WHERE `type` = 'article' AND `tag` = :tag)",
                    ['tag' => $tag]
                )
            );
            return $this->_text(
                $this->makeXml(
                    Mvc::getConfig('site|name', PROJECT_NAME),
                    Mvc::getConfig('site|description', PROJECT_NAME),
                    'https://hefang.link/tag/' . $tag,
                    'utf-8', $pager->getData()
                ),
                TextView::XML
            );
        } catch (SqlException $e) {
            return $this->_exception($e);
        }
    }

    private function makeData(array $default): array
    {
        return array_merge([
            'title' => '',
            'link' => '',
            'description' => '',
            'articles' => []
        ], $default);
    }

    private function makeXml(string $title, string $description, string $language, string $link, array $items): string
    {
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->createElement('rss');

        $rss = $xml->createElement('rss');
        $channel = $xml->createElement('channel');
        $channel->appendChild($xml->createElement('title', $title));
        $channel->appendChild($xml->createElement('description', $description));
        $channel->appendChild($xml->createElement('language', $language));
        $channel->appendChild($xml->createElement('link', $link));
        foreach ($items as $item) {
            if (!($item instanceof ArticleModel)) continue;
            $path = $item->getAlias() ?: $item->getId();
            $nodeItem = $xml->createElement('item');
            $nodeItem->appendChild($xml->createElement('title', $item->getTitle()));
            $nodeItem->appendChild($xml->createElement('description', $item->getDescription()));
            $nodeItem->appendChild($xml->createElement('link', "https://hefang.link/article/{$path}.html"));
            $nodeItem->appendChild($xml->createElement('pubDate', date('r', strtotime($item->getPostTime()))));
            $channel->appendChild($nodeItem);
        }

        $rss->setAttribute('version', '2.0');
        $rss->appendChild($channel);
        $xml->appendChild($rss);
        return $xml->saveXML();
    }
}