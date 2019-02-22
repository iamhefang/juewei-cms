<?php
defined('PHP_MVC') or die('Access Refused');

return [
    'debug.enable' => true,
    'pathinfo.type' => 'PATH_INFO',
    'project.package' => 'link.hefang.site',
    'database.enable' => true,
    'database.class' => 'link.hefang.mvc.databases.Mysql',
    'database.host' => 'localhost',
    'database.port' => null,
    'database.username' => 'root',
    'database.password' => '123456',
    'database.charset' => 'utf8mb4',
    'database.database' => 'blog',
    'password.salt' => '89389283sofisdkfjlkjg832flskdg2',
    'default.module' => 'blog',
    'default.controller' => 'home',
    'default.action' => 'index',
    'default.page.size' => 20,
    'default.charset' => 'utf-8',
    'default.theme' => 'simple-white',
    'default.locale' => 'zh_CN',
];
