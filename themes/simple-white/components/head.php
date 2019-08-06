<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<meta name="renderer" content="webkit"/>
<meta name="renderer-force" content="webkit"/>
<title>{:title}-{:name}</title>
<meta name="author" content="hefang">
<meta name="keywords" content="{:keywords}">
<!--<meta name="theme-color" content="#fefefe">-->

<script>
	if ("{mvc:site|auto_jump_to_https:true}" === "1" && location.protocol !== "https:") {
		location.href = location.href.replace("http://", "https://")
	}
</script>
<meta name="description" content="{:description}">
<meta baidu-gxt-verify-token="c0a17dac43d072cb520c07d62039225e">
<link rel="dns-prefetch" href="//cdn.hefang.link">
<!--[if lte IE 9]>
<link rel="stylesheet" href="{:themeUrl}/css/ie8.css">
<script src="//cdn.hefang.link/html5shiv/html5shiv.min.js"></script>
<![endif]-->
<link crossorigin="anonymous" rel="stylesheet" href="//cdn.hefang.link/hefang-ui-css/1.1.5/hefang-ui.css">
<link rel="stylesheet" href="{:themeUrl}/css/swiper.css">
<link rel="stylesheet" href="{:themeUrl}/css/index.css">

<script crossorigin="anonymous" src="//cdn.hefang.link/core-js/core.min.js"></script>
<script crossorigin="anonymous" src="//cdn.hefang.link/jquery/3.3.1/jquery.min.js"></script>
<script crossorigin="anonymous" src="//cdn.hefang.link/react/16.3.2/react.production.min.js"></script>
<script crossorigin="anonymous"
		  src="//cdn.hefang.link/react-dom/16.3.2/react-dom.production.min.js"></script>
<script crossorigin="anonymous" src="//cdn.hefang.link/hefang-js/1.1.7/index.js"></script>
<script crossorigin="anonymous" src="//cdn.hefang.link/hefang-ui-react/1.0.16/index.js"></script>
<script src="{:themeUrl}/js/hefang-ui-jquery-swiper.js"></script>
<script defer src="{:themeUrl}/js/common.js"></script>
<script defer src="{:themeUrl}/js/index.js"></script>
<script>
	var _hmt = _hmt || [];
	(function () {
		var hm = document.createElement("script");
		hm.src = "https://hm.baidu.com/hm.js?4a89ce8c9d29f5614929f5dd5b54d279";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
	})();
</script>

<link rel="manifest" href="/manifest.json">
