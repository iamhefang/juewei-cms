import {ApiResult, getLocalStorage, setLocalStorage, Toast} from "hefang-ui-react";

const $win = $(window)
    , $doc = $(document)
    , $navbar = $("#navbar")
    , $toggleNav = $("#toggleSideNav")
    , $navItems = $('.navbar-item')
    , $main = $("#main")
    , $aside = $("#aside")
    , $sideContainer = $('.aside-container')
    , $panelToggle = $('.panel-btn-toggle')
    , $articleUrl = $('#articleUrl')
    , $upArticle = $('.up-article')
    , sideHeight = $aside.outerHeight();

let sideWidth = $aside.outerWidth(), mainWidth = $main.outerWidth(), navHeight = $navbar.outerHeight();

$upArticle.on('click', function () {
    const $me = $(this)
        , id = $me.attr('data-id')
        , upped = getLocalStorage('upped', []) as string[];
    if (upped.indexOf(id) !== -1) {
        Toast.show('您已点赞过该文章', {duration: 10000000});
        return;
    }
    $.getJSON("/api/content/article/up.json", {id}, function (res: ApiResult<string>) {
        if (res.success) {
            const $count = $me.find('.count')
                , count = +$count.text();
            $count.text(count + 1);
            upped.push(id);
            setLocalStorage('upped', upped);
            Toast.show('点赞成功');
        }
    })
});

$toggleNav.on("click", function () {
    toggleOpen($navbar)
});

$navItems.on('click', function () {
    $navbar.attr('data-open', 'false')
});

$aside.css('left', $main.outerWidth());

$panelToggle.on("click", function (e) {
    const $panel = $(this).parents(".panel")
        , $content = $panel.find(".panel-content")
        , isOpen = $panel.attr('data-open') === 'true';
    $content.slideToggle(400);
});

$articleUrl.val(location.href.replace(location.hash, ''))
    .on('focus', function () {
        (this as HTMLInputElement).select();
        try {
            if (document.execCommand("copy", false, location.href)) {
                setTimeout(function () {
                    Toast.show("地址已复制到粘贴板")
                }, 300)
            }
        } catch (e) {
        }
    });

$doc.on("scroll", function () {
    const docScrollTop = $doc.scrollTop();
    //给导航栏添加/删除阴影
    if (docScrollTop > navHeight) {
        $navbar.hasClass('shadow') || $navbar.addClass("shadow")
    } else if (docScrollTop <= navHeight / 3) {
        $navbar.hasClass('shadow') && $navbar.removeClass("shadow");
    }

    //固定侧边
    if (window.innerWidth < 16 * 32) {
        return;
    }
    if (docScrollTop > sideHeight - window.innerHeight) {
        // console.log("need fixed");
        // $main.removeClass("flex-1")
        //     .css('width', mainWidth);
        // $sideContainer.css({
        //     position: 'absolute',
        //     right: 0,
        //     bottom: 0
        // })
    }

});

$win.on("resize", function () {
    sideWidth = $aside.outerWidth();
    mainWidth = $main.outerWidth();
    navHeight = $navbar.outerHeight()
});

function toggleOpen($dom: JQuery, open: boolean = null) {
    const willOpen = open === null ? ($dom.attr("data-open") === 'false') : open;
    $dom.attr('data-open', willOpen + '')
}

