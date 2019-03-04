<?php
$pageIndex = pager($pager)->getPageIndex();
$pageSize = pager($pager)->getPageSize();
$total = pager($pager)->getTotal();
$pages = ceil($total / $pageSize);
$start = $pageIndex - 3;
$end = $pageIndex + 3;
$start < 1 and $start = 1;
$end > $pages and $end = $pages;
?>

<div class="block ">
    <div class="hui-pager">
        <a href="?pageIndex=1&pageSize=<?= $pageSize ?>" class="hui-pager-item">首页</a>
        <?php for ($page = $start; $page <= $end; $page++) { ?>
            <a href="?pageIndex=<?= $page ?>" class="hui-pager-item"><?= $page ?></a>
        <?php } ?>
        <a href="?pageIndex=<?= $pages ?>" class="hui-pager-item">尾页</a>
    </div>
</div>

