<p class="info">
    {func:articleDate($article)} |
    <i class="fa fa-eye"></i> <?= article($article)->getReadCount() ?>
    <a href="javascript:;" class="up-article" data-id="<?= $article->getId() ?>"><i
                class="fa fa-thumbs-up"></i> <span class="count"><?= article($article)->getUpCount() ?></span></a>
    <a href="javascript:;"><i class="fa fa-comment"></i> <?= article($article)->getCommentCount() ?></a> |
    <i class="fa fa-tags"></i>
    <?php foreach (article($article)->getTags() as $tag) { ?>
        <a href="{:urlPrefix}/tag/{:tag}">{func:highlight($tag,$highlight)}</a>,
    <?php } ?>
</p>