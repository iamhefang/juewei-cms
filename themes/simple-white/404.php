<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
    <link rel="stylesheet" href="/statics/github-markdown.css">
</head>
<body>
{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <div class="block markdown-body">
            <h1>访问的内容不存在</h1>
            <div>可能的原因有：</div>
            <ol>
                <li>网址输入有误，请检查访问的地址。</li>
                <li>该内容还未正式发表，请等待正式发表</li>
                <li>该内容已被删除，需要查看请和作者取得联系。</li>
            </ol>
        </div>
        <div class="block markdown-body">
            <h3>使用RSS订阅我的博客</h3>
            <div>
                <p>本站支持RSS订阅，及时获取最新文章。</p>
                <p>订阅教程：<a href="/page/rss.html">使用RSS订阅何方博客</a></p>
            </div>
            <h3>大家对这些内容比较感兴趣</h3>
            <ol>
                <?php $topArticles = topArticle('hot', 10); ?>
                <?php if (count($topArticles) > 0) { ?>
                    {each:topArticles as article}
                    <li>
                        <a href="{:urlPrefix}/article/<?= $article->getAlias() ?: $article->getId() ?>.html">
                            {article.getTitle()}
                        </a>
                    </li>
                    {endeach}
                <?php } ?>
            </ol>
            <h3>这些是最新发表的内容，也看看吧</h3>
            <ol>
                <?php $topArticles = topArticle('new', 10); ?>
                <?php if (count($topArticles) > 0) { ?>
                    {each:topArticles as article}
                    <li>
                        <a href="{:urlPrefix}/article/<?= $article->getAlias() ?: $article->getId() ?>.html">
                            {article.getTitle()}
                        </a>
                    </li>
                    {endeach}
                <?php } ?>
            </ol>
        </div>
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}
</body>
</html>