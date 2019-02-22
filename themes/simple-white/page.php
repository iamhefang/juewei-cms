<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
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
