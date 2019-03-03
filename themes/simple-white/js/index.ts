import {ApiResult, getLocalStorage, setLocalStorage, Toast} from "hefang-ui-react";

$(function () {
    const $win = $(window)
        , $doc = $(document)
        , $htmlBody = $('html,body')
        , $navbar = $("#navbar")
        , $toggleNav = $("#toggleSideNav")
        , $navItems = $('.navbar-item')
        , $main = $("#main")
        , $aside = $("#aside")
        , $scroll = $('.scroll')
        , $scrollCanvas = $scroll.find('canvas')
        , $scrollSpan = $scroll.find('span')
        , $panelToggle = $('.panel-btn-toggle')
        , $panels = $('.panel')
        , $upArticle = $('.up-article')
        , $footer = $('.footer')
        , $images = $('img')
        , ctx = $scrollCanvas.length > 0 ? ($scrollCanvas[0] as HTMLCanvasElement).getContext("2d") : null;

    let sideWidth = $aside.outerWidth()
        , mainWidth = $main.outerWidth()
        , sideHeight = $aside.outerHeight()
        , navHeight = $navbar.outerHeight()
        , docHeight = $doc.outerHeight()
        , footerHeight = $footer.outerHeight()
        , mainHeight = $main.outerHeight()
        , isFixedSide = false;

    $images.on('load', function () {
        docHeight = $doc.outerHeight();
        mainHeight = $main.outerHeight();
    });

    $scroll.on("click", function () {
        $htmlBody.animate({scrollTop: 0})
    });

    $upArticle.on('click', function () {
        const $me = $(this)
            , id = $me.attr('data-id')
            , upped = getLocalStorage('upped', []) as string[];
        if (upped.indexOf(id) !== -1) {
            Toast.show('您已点赞过该文章');
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
    }).each(function () {
        const $me = $(this)
            , href = $me.attr('href');
        if (location.href.indexOf(href) !== -1 && href !== '/') {
            $me.addClass('active')
        }
    });

    // $aside.css('left', $main.outerWidth());

    $panels.filter(function () {
        return $(this).attr('data-open') === 'false';
    }).find('.panel-content').hide();

    $panelToggle.on("click", function (e) {
        const $panel = $(this).parents(".panel")
            , $content = $panel.find(".panel-content")
            , isOpen = $panel.attr('data-open') !== 'false';
        $panel.attr('data-open', isOpen ? 'false' : 'true');
        $content.slideToggle(400, function () {
            sideHeight = $aside.outerHeight();
            $doc.trigger('scroll')
        });
    });
    $doc.on("scroll", function () {
        const docScrollTop = $doc.scrollTop()
            , parcent = docScrollTop / (docHeight - window.innerHeight) * 100
            , footerTop = footerHeight - (docHeight - docScrollTop - window.innerHeight);
        //给导航栏添加/删除阴影
        if (docScrollTop > navHeight) {
            $navbar.hasClass('shadow') || $navbar.addClass("shadow")
        } else if (docScrollTop <= navHeight / 3) {
            $navbar.hasClass('shadow') && $navbar.removeClass("shadow");
        }

        if (docScrollTop > 100) {
            $scroll.fadeIn()
        } else {
            $scroll.fadeOut()
        }
        $scrollSpan.text(parcent.toFixed(0));
        if (ctx) {
            ctx.fillStyle = "white";
            ctx.fillRect(0, 0, 256, 256);
            ctx.lineWidth = 40;
            ctx.strokeStyle = "#00a2dd";
            ctx.beginPath();
            ctx.arc(128, 128, 128, 0, Math.PI * 2 / 100 * parcent);
            ctx.stroke();
            ctx.closePath();
        }
        if (sideHeight > mainHeight) {
            $main.height(sideHeight)
        }
        //固定侧边
        if (window.innerWidth < 16 * 32 || sideHeight >= mainHeight) {
            return;
        }
        if (sideHeight < window.innerHeight - navHeight - (footerTop > 0 ? footerTop : 0)) {
            $aside.css({
                "position": 'fixed',
                'bottom': 'auto'
            });
            isFixedSide = true;
        } else if (docScrollTop > sideHeight - window.innerHeight + navHeight && !isFixedSide) {
            $aside.css({
                'position': 'fixed',
                'bottom': '1rem'
            });
            isFixedSide = true;
        } else if (docScrollTop <= sideHeight - window.innerHeight + 16 + navHeight && isFixedSide) {
            $aside.css({
                position: 'relative',
                bottom: 'auto'
            });
            isFixedSide = false;
        } else if (footerTop > 0 && isFixedSide) {
            $aside.css({
                bottom: footerTop + 16
            })
        }

    }).trigger('scroll');

    $win.on("resize", function () {
        sideWidth = $aside.outerWidth();
        mainWidth = $main.outerWidth();
        navHeight = $navbar.outerHeight();
        footerHeight = $footer.outerHeight();
        docHeight = $doc.outerHeight();
        sideHeight = $aside.outerHeight()
    });

    function toggleOpen($dom: JQuery, open: boolean = null) {
        const willOpen = open === null ? ($dom.attr("data-open") === 'false') : open;
        $dom.attr('data-open', willOpen + '')
    }
});