const cacheName = "hefang-blog";
const appShellFiles = [
	"//cdn.hefang.link/hefang-ui-css/1.1.5/hefang-ui.css",
	"//cdn.hefang.link/core-js/core.min.js",
	"//cdn.hefang.link/jquery/3.3.1/jquery.min.js",
	"//cdn.hefang.link/react/16.3.2/react.production.min.js",
	"//cdn.hefang.link/react-dom/16.3.2/react-dom.production.min.js",
	"//cdn.hefang.link/hefang-js/1.1.7/index.js",
	"//cdn.hefang.link/hefang-ui-react/1.0.16/index.js",
	"//cdn.hefang.link/code-prettify/0.1.0/prettify.js",
	"//hefang.link/admin/comment.js",
	"//cdn.hefang.link/ace/1.4.3/src-min/theme-github.js",
	"//cdn.hefang.link/ace/1.4.3/src-min/mode-markdown.js",
	"//cdn.hefang.link/hefang-ui-mdeditor/1.0.6/index.js",
	"//cdn.hefang.link/katex/0.10.1/katex.min.js",
	"//cdn.hefang.link/marked/0.6.1/marked.min.js",
	"//cdn.hefang.link/ace/1.4.3/src-min/ace.js",
	"//hefang.link/themes/simple-white/js/hefang-ui-jquery-swiper.js",
	"//cdn.hefang.link/hefang-images/alipay_shoukuanma.jpg",
	"//cdn.hefang.link/hefang-images/wechat_zanshangma.jpg",
	"//cdn.hefang.link/hefang-images/qrcode_hefangblog.jpg",
	"//cdn.hefang.link/github-markdown-css/3.0.1/github-markdown.css",
	"//cdn.hefang.link/code-prettify/0.1.0/prettify.css",
	"//hefang.link/themes/simple-white/css/index.css",
	"//cdn.hefang.link/hefang-ui-css/1.1.5/webfonts/fa-brands-400.woff2",
	"//cdn.hefang.link/hefang-ui-css/1.1.5/webfonts/fa-solid-900.woff2",
	"//cdn.hefang.link/hefang-images/logos/24x24.png",
	"//cdn.hefang.link/hefang-images/logos/44x44.png",
	"//cdn.hefang.link/hefang-images/logos/48x48.png",
	"//cdn.hefang.link/hefang-images/logos/50x50.png",
	"//cdn.hefang.link/hefang-images/logos/76x76.png",
	"//cdn.hefang.link/hefang-images/logos/88x88.png",
	"//cdn.hefang.link/hefang-images/logos/96x96.png",
	"//cdn.hefang.link/hefang-images/logos/120x120.png",
	"//cdn.hefang.link/hefang-images/logos/144x144.png",
	"//cdn.hefang.link/hefang-images/logos/150x150.png",
	"//cdn.hefang.link/hefang-images/logos/152x152.png",
	"//cdn.hefang.link/hefang-images/logos/180x180.png",
	"//cdn.hefang.link/hefang-images/logos/192x192.png",
	"//cdn.hefang.link/hefang-images/logos/300x300.png",
	"//hefang.link/favicon.ico"
];

self.addEventListener("install", function (e) {
	// console.info("[Service Worker] Installed");
	e.waitUntil(caches.open(cacheName).then(function (cache) {
		return cache.addAll(appShellFiles)
	}));
});

self.addEventListener('fetch', function (e) {
	if (e.request.url.indexOf("/api/") !== -1) {
		return fetch(e.request).then(response => response)
	}
	e.respondWith(caches.match(e.request).then(function (cachedResponse) {
		// console.log('[Service Worker] Fetching resource: ' + e.request.url);
		return cachedResponse || fetch(e.request).then(function (newResponse) {
			return caches.open(cacheName).then(function (cache) {
				// console.log('[Service Worker] Caching new resource: ' + e.request.url);
				cache.put(e.request, newResponse.clone());
				return newResponse;
			});
		});
	}));
});

self.addEventListener('activate', function (e) {
	e.waitUntil(caches.keys().then(function (keyList) {
		return Promise.all(keyList.map(function (key) {
			if (cacheName.indexOf(key) === -1) {
				return caches.delete(key);
			}
		}));
	}));
});
