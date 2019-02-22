<?php defined('PROJECT_NAME') or die("Access Refused"); ?>
<aside class="aside" id="aside">
    <div class="panel" data-open="true">
        <div class="panel-header display-flex-row">
            <div class="flex-1">
                <i class="fa fa-search"></i>
                搜索
            </div>
            <button class="no-border no-background panel-btn-toggle">
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        <div class="panel-content">
            {inc:search.php}
        </div>
    </div>
    <div class="panel">
        <div class="panel-header display-flex-row">
            <div class="flex-1">
                <i class="fa fa-info-circle"></i>
                关于何方
            </div>
            <button class="no-border no-background panel-btn-toggle">
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        <div class="panel-content">
            {mvc:site|about}
        </div>
    </div>
    <div class="panel" data-open="true">
        <div class="panel-header display-flex-row">
            <div class="flex-1">
                <i class="fa fa-tags"></i>
                标签({func:count($tags)})
            </div>
            <button class="no-border no-background panel-btn-toggle">
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        <div class="panel-content">
            {each:tags as tag}
            <?php
            $fontSize = $tag->getContentCount();
            if ($fontSize > 20) $fontSize = 20;
            $fontSize += 12;
            ?>
            <a href="/index.php/tag/{tag.getTag()}" class="tag-link" style="font-size: {:fontSize}px">
                {tag.getTag()}({tag.getContentCount()})
            </a>
            {endeach}
        </div>
    </div>
    <div class="panel">
        <div class="panel-header display-flex-row">
            <div class="flex-1">
                <i class="fab fa-weixin"></i>
                微信公众号
            </div>
            <button class="no-border no-background panel-btn-toggle">
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        <div class="panel-content">
            <img src="https://open.weixin.qq.com/qr/code?username=hefangblog" alt="" style="width: 100%">
        </div>
    </div>
</aside>