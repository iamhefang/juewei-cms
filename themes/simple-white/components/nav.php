<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
<nav class="navbar" id="navbar" data-open="false">
    <div class="container">
        <span class="navbar-brand">{:name}</span>
        <div class="navbar-container">
            <a href="/" class="navbar-item">
                <span><i class="fa fa-home"></i> 首页</span>
            </a>
            <a href="{:urlPrefix}/tools.html" class="navbar-item">
                <span><i class="fa fa-tools"></i> 工具</span>
            </a>
            <a href="{:urlPrefix}/page/rss.html" class="navbar-item">
                <span><i class="fa fa-rss"></i> RSS</span>
            </a>
            <a href="{:urlPrefix}/page/wechat-keywords.html" class="navbar-item">
                <span><i class="fab fa-weixin"></i> 微信关键字</span>
            </a>
            <a href="{:urlPrefix}/page/about.html" class="navbar-item">
                <span><i class="fa fa-info-circle"></i> 关于</span>
            </a>
            <?php if (($login instanceof \link\hefang\site\users\models\LoginModel) && $login->isAdmin()) { ?>
                <a href="/admin/index.html" class="navbar-item">
                    <span><i class="fa fa-tachometer-alt"></i> 后台管理</span>
                </a>
            <?php } ?>
        </div>
    </div>
    <button id="toggleSideNav"></button>
</nav>