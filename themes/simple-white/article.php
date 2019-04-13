<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {each:beforeHead as item}
    {:item}
    {endeach}
    {inc:components/head.php}
    <script>
        var contentId = '{article.getId()}',
            commentEnable = <?=$commentEnable ? 'true' : 'false'?>,
            commentCount = <?=$article->getCommentCount()?>,
            commentCaptchaEnable = <?=link\hefang\mvc\Mvc::getConfig('comment|captcha_enable', false) ? 'true' : 'false'?>
    </script>
    <script type="application/ld+json"><?= baiduJsonLD($article) ?></script>
    <script defer src="/admin/comment.js"></script>
    <link rel="stylesheet" href="//api.jueweikeji.com.cn/statics/code-prettify/0.1.0/prettify.css">
    <link rel="stylesheet" href="//api.jueweikeji.com.cn/statics/github-markdown-css/3.0.1/github-markdown.css">
    <script crossorigin="anonymous" src="//api.jueweikeji.com.cn/statics/code-prettify/0.1.0/prettify.js"></script>
    {each:afterHead as item}
    {:item}
    {endeach}
</head>
<body>
{each:beforeBody as item}
{:item}
{endeach}

{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <article class="block article">
            <h1>{article.getTitle()}</h1>
            {inc:components/articleInfo.php}
            <hr>
            {if:needPassword}
            <form style="max-width: 20rem;margin: 3rem auto" class="text-center" method="post">
                <i class="fa fa-lock" style="font-size: 5rem"></i>
                <label for="password" class="display-block" style="padding: 1rem">
                    {:needPasswordMessage}
                </label>
                <input type="password"
                       name="password"
                       id="password"
                       class="hui-input display-block text-center"
                       placeholder="请输入文章密码">
            </form>
            {else}
            <div class="markdown-body">
                <blockquote>
                    {article.getDescription()}
                </blockquote>
                <p></p>
                {article.getHtml()}
            </div>
            <script>
                prettyPrint();
            </script>
            {endif}
        </article>
        <div class="block">
            <?php if (strlen($article->getReprintFrom()) > 10) { ?>
                <label for="reprintFromUrl" class="display-block">该文章转载自</label>
                <input id="reprintFromUrl" type="text" class="hui-input display-block text-center" readonly
                       value="<?= $article->getReprintFrom() ?>"/>
            <?php } else { ?>
                <div>
                    版权声明：<a href="https://creativecommons.org/licenses/by-nc-nd/4.0/deed.zh" target="_blank">自由转载-非商用-非衍生-保持署名</a>
                </div>
                <div>发表时间：{func:articleDate($article)}</div>
                <div>相关标签：<?= join(", ", array_map(function ($tag) {
                        return "<a href='/tag/{$tag}'>{$tag}</a>";
                    }, $article->getTags())) ?></div>
            <?php } ?>
        </div>
        <div class="block appreciate text-center">
            <p>
                都到这儿了。点个赞，赞个赏呗！
            </p>
            <a class="hui-btn up-article" data-id="{article.getId()}">
                <i class="fa fa-thumbs-up"></i> 点赞
            </a>
            <a class="hui-btn" href="#appreciate">
                <i class="fa fa-hand-holding-usd"></i>
                赞赏
            </a>
        </div>
        <div id="commentContainer">评论组件加载中......</div>
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}

{inc:components/scroll.php}

{inc:components/appreciate.php}

{each:afterBody as item}
{:item}
{endeach}
</body>
</html>
