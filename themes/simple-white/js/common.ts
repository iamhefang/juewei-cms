const exports = {}, objMap = {
    'hefang-js': 'H',
    'hefang-ui-react': 'HuiReact',
    'react': "React",
    'react-dom': 'ReactDOM',
    'ace-builds': 'ace',
    'marked': 'marked',
    'hefang-ui-mdeditor': "HuiMarkdownEditor",
    'code-prettify': 'PR',
    'katex': 'katex'
};

function require(name: string) {
    if (name in objMap) {
        const objName = objMap[name];
        if (objName in window) {
            return window[objName];
        }
        throw new Error(`对象${objName}不存在`);
    }
    throw new Error(`库${name}未定义`);
}

function openIntent(url: string) {
    // const frame = document.createElement('iframe') as HTMLIFrameElement;
    // frame.src = url;
    // frame.style.display = 'none';
    // document.body.appendChild(frame);
    // setTimeout(function () {
    //     frame.remove()
    // }, 1000);

    const link = document.createElement('a') as HTMLAnchorElement;
    link.href = url;
    document.body.appendChild(link);
    link.click();
    setTimeout(function () {
        link.remove()
    }, 1000);
}

function loadJs(url: string, noCache: boolean = false) {
    const script = document.createElement('script') as HTMLScriptElement;
    script.src = url + (noCache ? `?noCache=${(new Date).getTime()}` : '');
    document.head.appendChild(script);
}

loadJs('/themes/simple-white/js/index.js');

