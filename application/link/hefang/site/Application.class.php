<?php

namespace link\hefang\site;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\cms\plugins\IPlugin;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\ApiResult;
use link\hefang\mvc\entities\Router;
use link\hefang\mvc\exceptions\ActionNotFoundException;
use link\hefang\mvc\exceptions\ControllerNotFoundException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\SimpleApplication;
use link\hefang\mvc\views\RedirectView;
use link\hefang\mvc\views\TextView;
use link\hefang\site\admin\models\ConfigModel;


class Application extends SimpleApplication
{
	const PREFIX_URL = '/url/';
	const PREFIX_FILES = '/files/';
	const PREFIX_PAGE = '/page/';
	const PREFIX_ARTICLE = '/article/';
	const PREFIX_TAG = '/tag/';
	const PREFIX_API = '/api/';
	const PREFIX_RSS = '/rss';
	const PATTERN_DATE = '/^\/\d{4}(\/\d{1,2}(\/\d{1,2}(\/\d{1,2})?)?)?\/?$/i';

	public static function plugins(): array
	{
		return [

		];
	}

	public function onInit()
	{
		$config = ConfigModel::all();
		return $config;
	}

	public function onRequest(string $path)
	{
		Mvc::getLogger()->debug($path);
		$router = null;
		foreach (self::plugins() as $plugin) {
			if (!($plugin instanceof IPlugin)) continue;
			$router = $plugin->onRequest($path);
			if ($router) return $router;
		}
		$paths = explode('/', rtrim($path, '/'));
		if (StringHelper::startsWith($path, true, self::PREFIX_RSS)) {
			if (count($paths) < 3) {

			} elseif ($paths[2] !== 'tag') {
				$router = new Router("blog", "rss", 'index', $paths[1], 'xml');
			} else {
				$router = new Router('blog', 'rss', 'tag', $paths[3], 'xml');
			}
		} elseif (count($paths) < 3) {

		} elseif (StringHelper::startsWith($path, true, self::PREFIX_FILES)) {
			$router = new Router(
				'content', 'file', 'get', CollectionHelper::last($paths)
			);
		} elseif (StringHelper::startsWith($path, true, self::PREFIX_URL)) {
			$router = new Router(
				'content', 'url', 'visit', CollectionHelper::last($paths)
			);
		} elseif (StringHelper::startsWith($path, true, self::PREFIX_TAG)) {
			$router = new Router(
				'blog', 'home', 'tag', CollectionHelper::last($paths)
			);
		} elseif (StringHelper::startsWith($path, true, self::PREFIX_ARTICLE)) {
			$paths = explode(".", CollectionHelper::last($paths));
			$router = new Router(
				'blog', 'home', 'article',
				CollectionHelper::first($paths),
				CollectionHelper::last($paths)
			);
		} elseif (StringHelper::startsWith($path, true, self::PREFIX_PAGE)) {
			$paths = explode(".", CollectionHelper::last($paths));
			$router = new Router(
				'blog', 'home', 'page',
				CollectionHelper::first($paths),
				CollectionHelper::last($paths)
			);
		} elseif (StringHelper::startsWith($path, true, self::PREFIX_API)) {
			$router = Router::parsePath('/' . substr($path, strlen(self::PREFIX_API)));
		} elseif (preg_match(self::PATTERN_DATE, $path)) {
			$month = 0;
			$day = 0;
			$year = $paths[1];
			$count = count($paths);
			if ($count > 2 && $paths[2]) {
				$month = $paths[2];
			}

			if ($count > 3 && $paths[3]) {
				$day = $paths[3];
			}

			$router = new Router('blog', 'home', 'date',
				json_encode([
					'year' => $year,
					'month' => $month,
					'day' => $day
				], JSON_UNESCAPED_UNICODE)
			);
		} else {
			$router = new Router('blog', 'home', '_404');
		}
		return $router;
	}

	public function onException(\Throwable $e)
	{
		foreach (self::plugins() as $plugin) {
			if (!($plugin instanceof IPlugin)) continue;
			$plugin->onException($e);
		}
		if (Mvc::isDebug()) {
			return new TextView(json_encode(new ApiResult(false, $e->getMessage()), JSON_UNESCAPED_UNICODE), TextView::JSON);
		} else if (($e instanceof ActionNotFoundException) || ($e instanceof ControllerNotFoundException)) {
			return new RedirectView("/404.html");
		} else {
			return new RedirectView("/500.html");
		}
	}
}
