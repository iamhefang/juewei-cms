<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
<aside class="aside-container">
    <div class="aside" id="aside">
        <div class="panel" data-open="true">
            <div class="panel-header display-flex-row">
                <div class="flex-1">
                    <i class="fa fa-search"></i>
                    搜索
                </div>
                <button class="no-border no-background panel-btn-toggle">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="panel-content">
                {inc:search.php}
            </div>
        </div>
        <div class="panel">
            <div class="panel-header display-flex-row">
                <div class="flex-1">
                    <i class="fa fa-info-circle"></i>
                    关于何方
                </div>
                <button class="no-border no-background panel-btn-toggle">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="panel-content">
                {mvc:site|about}
            </div>
        </div>
        <div class="panel" data-open="true">
            <div class="panel-header display-flex-row">
                <div class="flex-1">
                    <i class="fa fa-tags"></i>
                    标签({func:count($tags)})
                </div>
                <button class="no-border no-background panel-btn-toggle">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="panel-content">
                {each:tags as tag}
                <?php
                $fontSize = $tag->getContentCount();
                if ($fontSize > 20) $fontSize = 20;
                $fontSize += 12;
                ?>
                <a href="{:urlPrefix}/tag/{tag.getTag()}" class="tag-link" style="font-size: {:fontSize}px">
                    {tag.getTag()}
                </a>
                {endeach}
            </div>
        </div>
        <?php $topArticles = topArticle('new'); ?>
        <?php if (count($topArticles) > 0) { ?>
            <div class="panel">
                <div class="panel-header display-flex-row">
                    <div class="flex-1">
                        <i class="fa fa-newspaper"></i>
                        最新发表
                    </div>
                    <button class="no-border no-background panel-btn-toggle">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <div class="panel-content">
                    <ul class="margin-0" style="padding-left: 1.5rem;">
                        {each:topArticles as article}
                        <li>
                            <a href="{:urlPrefix}/article/<?= $article->getAlias() ?: $article->getId() ?>.html">
                                {article.getTitle()}
                            </a>
                        </li>
                        {endeach}
                    </ul>
                </div>
            </div>
        <?php } ?>

        <?php $topArticles = topArticle('hot'); ?>
        <?php if (count($topArticles) > 0) { ?>
            <div class="panel">
                <div class="panel-header display-flex-row">
                    <div class="flex-1">
                        <i class="fa fa-newspaper"></i>
                        最热文章
                    </div>
                    <button class="no-border no-background panel-btn-toggle">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <div class="panel-content">
                    <ul class="margin-0" style="padding-left: 1.5rem;">
                        {each:topArticles as article}
                        <li>
                            <a href="{:urlPrefix}/article/<?= $article->getAlias() ?: $article->getId() ?>.html">
                                {article.getTitle()}
                            </a>
                        </li>
                        {endeach}
                    </ul>
                </div>
            </div>
        <?php } ?>
        <div class="panel">
            <div class="panel-header display-flex-row">
                <div class="flex-1">
                    <i class="fab fa-weixin"></i>
                    微信公众号
                </div>
                <button class="no-border no-background panel-btn-toggle">
                    <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="panel-content">
                <img src="https://open.weixin.qq.com/qr/code?username=hefangblog" alt="" style="width: 100%">
            </div>
        </div>
    </div>
</aside>