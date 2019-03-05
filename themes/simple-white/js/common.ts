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