import {Toast} from "hefang-ui-react";

const $navbar = $("#navbar")
    , $toggleNav = $("#toggleSideNav")
    , $navItems = $('.navbar-item')
    , $main = $("#main")
    , $aside = $("#aside")
    , $panelToggle = $('.panel-btn-toggle')
    , $articleUrl = $('#articleUrl');

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


function toggleOpen($dom: JQuery, open: boolean = null) {
    const willOpen = open === null ? ($dom.attr("data-open") === 'false') : open;
    $dom.attr('data-open', willOpen + '')
}

