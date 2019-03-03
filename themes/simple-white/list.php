<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
</head>
<body data-theme="white">
{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <div class="block">
            <h1 class="margin-0">{:title}</h1>
        </div>
        {inc:components/list.php}
    </main>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}

{inc:components/scroll.php}
</body>
</html>