<form class="search-container" method="get" action="{:urlPrefix}/search.html">
	<input class="hui-input display-block" name="search" type="search" placeholder="要找什么? 搜搜试试" value="{:search}">
	<i class="fa fa-search hui-icon"></i>
</form>
<?php if (!empty($hotSearch)) { ?>
	<div class="search-hot">
		热搜: {each:hotSearch as item}
		<a href="{:urlPrefix}/search.html?search={:item}">{:item}</a>
		{endeach}
	</div>
<?php } ?>
