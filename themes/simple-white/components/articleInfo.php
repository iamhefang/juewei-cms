<p class="info">
    {func:articleDate($article)} |
    <i class="fa fa-eye"></i> <?= $article->getReadCount() ?>
    <a href="javascript:;" data-id="<?= $article->getId() ?>"><i
                class="fa fa-thumbs-up"></i> <span class="count"><?= $article->getUpCount() ?></span></a>
    <a href="javascript:;"><i class="fa fa-comment"></i> <?= $article->getCommentCount() ?></a> |
    <i class="fa fa-tags"></i>
    <?= join(", ", array_map(function ($tag) use ($urlPrefix) {
        return "<a href='{$urlPrefix}/tag/{$tag}'>{$tag}</a>";
    }, $article->getTags())) ?>
</p>