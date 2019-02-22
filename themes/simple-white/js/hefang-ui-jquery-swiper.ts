interface SwiperOption {
    timing: string
    autoPlay: boolean
    indicator: boolean
    current: number
}

$(function () {
    const $swiper = $('.hui-swiper[data-swiper=true]')
        , boolOpt = ['autoPlay', 'indicator'];

    $(window).on('resize', function () {
        $swiper.css('height', $swiper.width() / 2)
    }).trigger('resize');

    $swiper.each(function () {
        const $me = $(this)
            , $items = $me.find('.hui-swiper-item')
            , opt: SwiperOption = {timing: '', autoPlay: true, indicator: true, current: 0};

        for (const key in opt) {
            const val = $me.attr('data-' + key.toLowerCase());
            if (!val) continue;
            opt[key] = $.inArray(key, boolOpt) ? (val.toLowerCase() === 'true') : val;
        }

        if (opt.indicator) {
            let btns = '';
            for (let i = 0; i < $items.length; i++) {
                btns += `<button class="hui-swiper-indicator-item"></button>`;
            }
            $me.append(`<div class="hui-swiper-indicator">${btns}</div>`)
        }
        if (opt.autoPlay) {
            setInterval(function () {
                switchTo(1 + opt.current)
            }, 10000)
        }

        const $btnItem = $('.hui-swiper-indicator-item');

        $btnItem.each(function (index) {
            $(this).on("click", function () {
                switchTo(index);
            })
        });

        function switchTo(index: number) {
            if (index < 0) {
                index = 0;
            }
            if (index >= $items.length) {
                index = $items.length - 1
            }
            $items.each(function (idx: number) {
                if (idx === index) {
                    $(this).fadeIn()
                } else {
                    $(this).fadeOut()
                }
            });
            opt.current = index;
        }
    });
});