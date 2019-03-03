<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
    <script>
        var contentId = '{article.getId()}',
            commentEnable = <?=$commentEnable ? 'true' : 'false'?>,
            commentCount = <?=$article->getCommentCount()?>,
            commentCaptchaEnable = <?=link\hefang\mvc\Mvc::getConfig('comment|captcha_enable', false) ? 'true' : 'false'?>
    </script>
    <script src="/statics/ueditor/ueditor.parse.js"></script>
    <!--    <link id="syntaxhighlighter_css" rel="stylesheet" type="text/css"-->
    <!--          href="/statics/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css">-->


    <!--    <script id="syntaxhighlighter_js" src="/statics/ueditor/third-party/SyntaxHighlighter/shCore.js"-->
    <!--            type="text/javascript"-->
    <!--            defer="defer"></script>-->
    <script type="application/ld+json"><?= baiduJsonLD($article) ?></script>
    <script defer src="/admin/comment.js?nocache={func:rand(1,100000000)}"></script>
    <link rel="stylesheet" href="/statics/github-markdown.css">
    <link rel="stylesheet" href="/statics/code-prettify/prettify.css">
    <script src="/statics/code-prettify/prettify.js"></script>
</head>
<body>
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
                       class="hui-input display-block text-center"
                       placeholder="请输入文章密码">
            </form>
            {else}
            <div class="markdown-body">
                {article.getHtml()}
            </div>
            <script>
                prettyPrint();
            </script>
            {endif}
        </article>
        <div class="block">
            <?php if (strlen($article->getReprintFrom()) > 10) { ?>
                <p>该文章转载自</p>
                <input type="text" class="hui-input display-block text-center" readonly
                       value="<?= $article->getReprintFrom() ?>"/>
            <?php } else { ?>
                <!--                <label for="articleUrl" class="display-block form-group">该文章原创, 转载请注明出处</label>-->
                <!--                <input type="text" class="hui-input display-block text-center" id="articleUrl"/>-->

                <div>版权声明：<a href="https://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh">自由转载-非商用-非衍生-保持署名</a>
                </div>
                <div>发表时间：{func:articleDate($article)}</div>
                <div>相关标签：<?= join(", ", array_map(function ($tag) {
                        return "<a href='/tag/{$tag}'>{$tag}</a>";
                    }, $article->getTags())) ?></div>
            <?php } ?>
        </div>
        <div id="commentContainer">评论组件加载中......</div>
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}

{inc:components/scroll.php}
</body>
</html>
