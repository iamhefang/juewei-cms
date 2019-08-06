<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
{incOnce:components/functions.php}
<!doctype html>
<html lang="zh">
<head>
	{inc:components/head.php}
	<script type="application/ld+json"><?= baiduJsonLD($article) ?></script>
	<link rel="stylesheet" href="//cdn.hefang.link/code-prettify/0.1.0/prettify.css">
	<link rel="stylesheet" href="//cdn.hefang.link/github-markdown-css/3.0.1/github-markdown.css">
	<script crossorigin="anonymous" src="//cdn.hefang.link/code-prettify/0.1.0/prettify.js"></script>
</head>
<body>
{inc:components/nav.php}
<div class="container display-flex-row">
	<main class="main flex-1" id="main">
		<article class="block">
			<h1>{article.getTitle()}</h1>
			<hr>
			<div class="markdown-body">
				{article.getHtml()}
			</div>
		</article>
		<script>
			prettyPrint();
		</script>
	</main>
	{inc:components/aside.php}
</div>
{inc:components/footer.php}

{inc:components/scroll.php}
</body>
</html>
