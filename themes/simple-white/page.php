<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
    <script type="application/ld+json"><?=baiduJsonLD($article)?></script>
</head>
<body>
{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <article class="block">
            <h1>{article.getTitle()}</h1>
            <hr>
            {article.getContent()}
        </article>
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}
</body>
</html>
