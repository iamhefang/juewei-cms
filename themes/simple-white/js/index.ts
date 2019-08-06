import {ApiResult, Dialog, getLocalStorage, setLocalStorage, Toast} from "hefang-ui-react";

if ("serviceWorker" in navigator) {
	navigator.serviceWorker.register("/sw.js", {
		scope: "/"
	}).catch(function (error) {
		console.error("注册 Service Worker 失败", error)
	})
}

$(function () {
	//初始化常量
	const $win = $(window)
		, $doc = $(document)
		, $htmlBody = $('html,body')
		, $navbar = $("#navbar")
		, $toggleNav = $("#toggleSideNav")
		, $navItems = $('.navbar-item')
		, $main = $("#main")
		, $aside = $("#aside")
		, $goTOp = $('.scroll')
		, $scrollCanvas = $goTOp.find('canvas')
		, $scrollSpan = $goTOp.find('span')
		, $panelToggle = $('.panel-btn-toggle')
		, $panels = $('.panel')
		, $upArticle = $('.up-article')
		, $footer = $('.footer')
		, $images = $('img')
		, $pagerItem = $('.hui-pager-item')
		, $appreciate = $('#appreciate')
		, $appreciateBtn = $('.appreciate-btn')
		, $appreciateImgAlipay = $('.appreciate-img.alipay')
		, $appreciateImgWechat = $('.appreciate-img.wechat')
		, $btnAppreciateType = $appreciate.find("button[data-type]")
		, $link = $('a[href]')
		, ctx = $scrollCanvas.length > 0 ? ($scrollCanvas[0] as HTMLCanvasElement).getContext("2d") : null;

	//初始化变量
	let sideWidth = $aside.outerWidth()
		, mainWidth = $main.outerWidth()
		, sideHeight = $aside.outerHeight()
		, navHeight = $navbar.outerHeight()
		, docHeight = $doc.outerHeight()
		, footerHeight = $footer.outerHeight()
		, mainHeight = $main.outerHeight()
		, isFixedSide = false
		, resizeTimer = null;

	//赞赏按钮点击事件
	$btnAppreciateType.on('click', function () {
		const $me = $(this)
			, type = $me.attr('data-type');
		$btnAppreciateType.removeClass('active');
		$me.addClass('active');
		if (type === 'alipay') {
			$appreciateImgWechat.hide();
			$appreciateImgAlipay.show();
			openIntent("alipayqr://platformapi/startapp?saId=10000007&qrcode=https://qr.alipay.com/tsx03531q1dskovumpabd4a")
		} else {
			$appreciateImgWechat.show();
			$appreciateImgAlipay.hide();
		}
	});
	$appreciateBtn.on('click', function () {
		$appreciate.fadeOut();
		history.replaceState(null, document.title,
			location.href.replace(location.hash, ''))
	});
	if (location.hash === '#appreciate') {
		$appreciate.fadeIn()
	}
	$win.on('hashchange', function () {
		if (location.hash === '#appreciate') {
			$appreciate.fadeIn()
		} else {
			$appreciate.fadeOut()
		}
	});

	//图片加载后重新计算文档高度
	$images.on('load', function () {
		docHeight = $doc.outerHeight();
		mainHeight = $main.outerHeight();
	});

	//设置分页当前页不可点击
	$pagerItem.each(function () {
		const $me = $(this)
			, href = $me.attr('href');
		if (location.href.indexOf(href) !== -1) {
			$me.css({background: 'gray', color: 'white'})
				.attr('href', 'javascript:;')
		}
	});

	//点击回到顶部
	$goTOp.on("click", function () {
		$htmlBody.animate({scrollTop: 0})
	});

	//文章点选事件
	$upArticle.each(function () {
		const $me = $(this)
			, id = $me.attr('data-id')
			, upped = getLocalStorage('upped', []) as string[];
		if (upped.indexOf(id) !== -1) {
			$me.html(`<i class="fa fa-thumbs-up"></i> 已赞`)
				.addClass('active')
		}
	}).on('click', function () {
		const $me = $(this)
			, id = $me.attr('data-id')
			, upped = getLocalStorage('upped', []) as string[];
		if (upped.indexOf(id) !== -1) {
			Toast.show('您已点赞过该文章');
			return;
		}
		$.getJSON("/api/content/article/up.json", {id}, function (res: ApiResult<string>) {
			if (res.success) {
				const $count = $me.find('.count');
				$me.html(`<i class="fa fa-thumbs-up"></i> 已赞`)
					.addClass('active');
				setLocalStorage('upped', upped);
				Toast.show('点赞成功');
			}
		})
	});

	//手机端导航栏切换事件
	$toggleNav.on("click", function () {
		toggleOpen($navbar)
	});

	//在手机端点击导航后关闭导航栏
	$navItems.on('click', function () {
		$navbar.attr('data-open', 'false')
	}).each(function () {
		//高亮当前页面导航栏
		const $me = $(this)
			, href = $me.attr('href');
		if (location.href.indexOf(href) !== -1 && href !== '/') {
			$me.addClass('active')
		}
	});

	//设置侧边面板默认打开/关闭状态
	$panels.filter(function () {
		return $(this).attr('data-open') === 'false';
	}).find('.panel-content').hide();
	//侧边面板点击打开/关闭事件
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
	//文档滚动事件
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

		//文档滚动时计算滚动百分比
		if (docScrollTop > 100) {
			$goTOp.fadeIn()
		} else {
			$goTOp.fadeOut()
		}
		$scrollSpan.text((parcent > 100 ? 100 : parcent).toFixed(0));
		if (ctx) {
			ctx.fillStyle = "white";
			ctx.fillRect(0, 0, 256, 256);
			ctx.lineWidth = 40;
			ctx.strokeStyle = "#00a2dd";
			ctx.beginPath();
			ctx.arc(128, 128, 128, 0, Math.PI * 2 / 100 * (parcent > 100 ? 100 : parcent));
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

	//窗口大小改变时重新计算各个元素大小
	$win.on("resize", function () {
		sideWidth = $aside.outerWidth();
		mainWidth = $main.outerWidth();
		navHeight = $navbar.outerHeight();
		footerHeight = $footer.outerHeight();
		docHeight = $doc.outerHeight();
		sideHeight = $aside.outerHeight();
		resizeTimer && clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () {
			$doc.trigger('scroll')
		}, 300)
	});

	//外站链接点击事件
	$link.on('click', function (e) {
		const href = (this as HTMLAnchorElement).href;
		if (href === 'javascript:;') return;
		if (href.indexOf(location.host) !== -1) {
			return;
		}
		e.preventDefault();
		e.stopPropagation();
		Dialog.confirm("该链接非本站链接，链接内容和本站无关。确定要打开吗？", "非本站链接", () => {
			window.open(href, '_blank')
		}, {
			width: 280
		});
		return false;
	});

	function toggleOpen($dom: JQuery, open: boolean = null) {
		const willOpen = open === null ? ($dom.attr("data-open") === 'false') : open;
		$dom.attr('data-open', willOpen + '')
	}
});
