const exports = {}, objMap = {
    'hefang-js': 'H',
    'hefang-ui-react': 'HuiReact',
    'react': "React",
    'react-dom': 'ReactDOM',
    'ace-builds': 'ace'
};

function require(name: string) {
    if (name in objMap) {
        return window[objMap[name]];
    }
    throw new Error(`对象${name}不存在`);
}