<!doctype html>
<html lang="zh">
<head>
	{inc:components/head.php}
</head>
<body data-theme="white">
{inc:components/nav.php}
<div class="container display-flex-row">
	<main class="main flex-1" id="main">
		<div class="block search-mobile">
			{inc:components/search.php}
		</div>
		<div class="block">
			<h1>出现错误</h1>
		</div>
		<div class="block">
			{:message}
		</div>
	</main>
	{inc:components/aside.php}
</div>
{inc:components/footer.php}
</body>
</html>
