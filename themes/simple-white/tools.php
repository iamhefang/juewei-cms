<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
    {inc:components/head.php}
</head>
<body>
{inc:components/nav.php}
<div class="container display-flex-row">
    <main class="main flex-1" id="main">
        <div class="block">
            <h2>
                工具组件加载中。。。。
            </h2>
        </div>
    </main>
    <script src="/admin/tools.js"></script>
    {inc:components/aside.php}
</div>
{inc:components/footer.php}

{inc:components/scroll.php}
</body>
</html>
