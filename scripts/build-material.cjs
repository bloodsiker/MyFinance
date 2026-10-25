const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const output = path.join(root, 'public/material');
fs.mkdirSync(output, { recursive: true });
const assets = [
    ['resources/js/material/app.js', 'app.js'],
    ['resources/js/material/domain.js', 'domain.js'],
    ['resources/css/material/app.css', 'app.css'],
];
const applicationSources = [
    'resources/js/material/core/ApiClient.js',
    'resources/js/material/core/HistoryRouter.js',
    'resources/js/material/state/CabinetStore.js',
    'resources/js/material/services/ToastService.js',
    'resources/js/material/controllers/DashboardController.js',
    'resources/js/material/controllers/TransactionController.js',
    'resources/js/material/controllers/DialogController.js',
    'resources/js/material/controllers/TaskController.js',
    'resources/js/material/controllers/ExchangeController.js',
    'resources/js/material/controllers/AuthController.js',
    'resources/js/material/controllers/TelegramController.js',
    'resources/js/material/presentation/CabinetViewModel.js',
    'resources/js/material/presentation/IconComponent.js',
    'resources/js/material/presentation/CustomSelectComponent.js',
    'resources/js/material/presentation/ModalComponent.js',
];

function build() {
    for (const [source, name] of assets) fs.copyFileSync(path.join(root, source), path.join(output, name));
    const application = applicationSources.map(source => fs.readFileSync(path.join(root, source), 'utf8')).join('\n');
    fs.writeFileSync(path.join(output, 'application.js'), application);

    // Vue is served locally, without a CDN.
    try {
        fs.copyFileSync(require.resolve('vue/dist/vue.global.prod.js'), path.join(output, 'vue.global.prod.js'));
        fs.copyFileSync(path.join(path.dirname(require.resolve('vue/package.json')), 'LICENSE'), path.join(output, 'vue.LICENSE'));
    } catch (error) {
        if (!fs.existsSync(path.join(output, 'vue.global.prod.js'))) throw error;
        console.log('Using the vendored Vue runtime in public/material.');
    }
    console.log('Independent Material/Vue application built in public/material.');
}

build();

if (process.argv.includes('--watch')) {
    for (const directory of ['resources/js/material', 'resources/css/material']) {
        fs.watch(path.join(root, directory), { recursive: true }, () => {
            build();
            console.log('Watching Material sources.');
        });
    }
    console.log('Watching Material sources.');
}
