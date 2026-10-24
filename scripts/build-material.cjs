const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const output = path.join(root, 'public/material');
fs.mkdirSync(output, { recursive: true });
for (const [source, name] of [
    ['resources/js/material/app.js', 'app.js'],
    ['resources/js/material/domain.js', 'domain.js'],
    ['resources/css/material/app.css', 'app.css'],
]) fs.copyFileSync(path.join(root, source), path.join(output, name));
// Vue is the only frontend dependency. It is served locally, without a CDN.
try {
    fs.copyFileSync(require.resolve('vue/dist/vue.global.prod.js'), path.join(output, 'vue.global.prod.js'));
    fs.copyFileSync(path.join(path.dirname(require.resolve('vue/package.json')), 'LICENSE'), path.join(output, 'vue.LICENSE'));
} catch (error) {
    if (!fs.existsSync(path.join(output, 'vue.global.prod.js'))) throw error;
    console.log('Using the vendored Vue runtime in public/material.');
}
console.log('Independent Material/Vue application built in public/material.');
if (process.argv.includes('--watch')) {
    for (const directory of ['resources/js/material', 'resources/css/material']) {
        fs.watch(path.join(root, directory), () => {
            for (const [source, name] of [
                ['resources/js/material/app.js', 'app.js'],
                ['resources/js/material/domain.js', 'domain.js'],
                ['resources/css/material/app.css', 'app.css'],
            ]) fs.copyFileSync(path.join(root, source), path.join(output, name));
            console.log('Material assets updated.');
        });
    }
    console.log('Watching Material sources.');
}
