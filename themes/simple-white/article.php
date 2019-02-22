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
    <script defer src="/admin/comment.js?nocache={func:rand(1,100000000)}"></script>
</head>
<body>
{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <article class="block">
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
            {article.getContent()}
            {endif}
        </article>
        <div class="block text-center">
            <?php if (strlen(article($article)->getReprintFrom()) > 10) { ?>
                <p>该文章转载自</p>
                <input type="text" class="hui-input display-block text-center" readonly
                       value="<?= article($article)->getReprintFrom() ?>"/>
            <?php } else { ?>
                <label for="articleUrl" class="display-block form-group">该文章原创, 转载请注明出处</label>
                <input type="text" class="hui-input display-block text-center" id="articleUrl"/>
            <?php } ?>
        </div>
        <div id="commentContainer">评论组件加载中......</div>
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}
</body>
</html>
