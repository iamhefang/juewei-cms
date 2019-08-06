<?php foreach (pager($pager)->getData() as $article) { ?>
	<div class="block article-list-item">
		<a href="{:urlPrefix}/article/<?= article($article)->getAlias() ?: article($article)->getId() ?>.html">
			<h2 class="title">
				<?php if (article($article)->isDraft()) { ?>
					<i class="mark">草稿</i>
				<?php } elseif (strlen(article($article)->getReprintFrom()) < 10) { ?>
					<i class="mark">原创</i>
				<?php } ?>
				<?php if (!\link\hefang\helpers\StringHelper::isNullOrBlank($article->getPassword())) { ?>
					<i class="mark">加密</i>
				<?php } ?>
				{func:highlight($article->getTitle(),$highlight)}
			</h2>
			<p class="description">
				{func:highlight($article->getDescription(),$highlight)}
			</p>
		</a>
		{inc:articleInfo.php}
	</div>
<?php } ?>
<?php if (empty(pager($pager)->getData())) { ?>
	<div class="block">
		<h1 class="margin-0">暂无内容</h1>
	</div>
<?php } ?>
{inc:pager.php}
