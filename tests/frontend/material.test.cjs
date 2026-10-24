const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const root = path.resolve(__dirname, '../..');
const context = { console, URL };
vm.createContext(context);
vm.runInContext(fs.readFileSync(path.join(root, 'resources/js/material/domain.js'), 'utf8'), context);
const D = context.FinanceDomain;

test('navigation supports direct links and deployment in a subdirectory', () => {
    assert.equal(D.route('/month/17', 'https://finance.test').name, 'month');
    assert.equal(D.route('/month/17', 'https://finance.test').id, 17);
    assert.equal(D.route('/finance/year/4', 'https://finance.test/finance').name, 'year');
    assert.equal(D.route('/finance/year/4', 'https://finance.test/finance').id, 4);
    assert.equal(D.route('/tasks/2026-10-24', '').name, 'tasks');
    assert.equal(D.route('/forgot-password', '').name, 'forgot');
    assert.equal(D.route('/reset-password/token', '').name, 'reset');
    assert.equal(D.route('/categories/', '').name, 'categories');
    assert.equal(D.route('/users', '').name, 'users');
});
test('calendar starts Monday, includes leap day and handles year boundaries', () => {
    const leap = D.calendar(new Date(2024, 1, 1, 12));
    assert.equal(leap.length, 42);
    assert.equal(new Date(leap[0].key + 'T12:00:00').getDay(), 1);
    assert.ok(leap.some(day => day.key === '2024-02-29' && !day.outside));
    const january = D.calendar(new Date(2027, 0, 1, 12));
    assert.ok(january[0].key.startsWith('2026-12'));
    assert.equal(new Set(january.map(day => day.key)).size, 42);
});
test('summary and progress handle empty and exceeded budgets', () => {
    assert.equal(D.totals([]).budget, 0);
    assert.equal(D.totals([{ budget: '100', spent: '125' }, { budget: 50, spent: 0 }]).budget, 150);
    assert.equal(D.progress({ budget: 0, spent: 0 }), 0);
    assert.equal(D.progress({ budget: 0, spent: 10 }), 100);
    assert.equal(D.progress({ budget: 100, spent: 150 }), 100);
    assert.equal(D.progress({ budget: 100, spent: 25 }), 25);
});
test('all currency inputs can drive conversion; invalid rates never produce Infinity', () => {
    const rates = { cny_usdt: 0.14, usdt_uah: 40 };
    const result = D.convert(100, 'cny', rates);
    assert.ok(Math.abs(result.usd - 14) < 1e-10);
    assert.ok(Math.abs(result.uah - 560) < 1e-10);
    assert.ok(Math.abs(D.convert(560, 'uah', rates).cny - 100) < 1e-10);
    assert.equal(D.convert(14, 'usd', rates).uah, 560);
    assert.equal(D.convert(1, 'usd', { cny_usdt: 0, usdt_uah: 40 }), null);
    assert.equal(D.convert('invalid', 'cny', rates), null);
});
test('the complete application and dialog templates compile with the pinned Vue runtime', () => {
    const errors = [];
    const sandbox = { FinanceDomain: D, console: { ...console, error: error => errors.push(error) },
        document: { createElement: () => ({}), getElementById: () => ({ textContent: JSON.stringify({base:'https://finance.test',user:null}) }) },
    };
    vm.createContext(sandbox);
    vm.runInContext(fs.readFileSync(path.join(root, 'public/material/vue.global.prod.js'), 'utf8'), sandbox);
    let app;
    sandbox.Vue.createApp = component => ({ mount: () => { app = component; } });
    vm.runInContext(fs.readFileSync(path.join(root, 'resources/js/material/app.js'), 'utf8'), sandbox);
    function compile(component) {
        sandbox.Vue.compile(component.template, { decodeEntities: value => value, onError: error => { throw error; } });
        for (const child of Object.values(component.components || {})) compile(child);
    }
    compile(app);
    assert.deepEqual(errors, []);
});


test('transaction edit dates use Kyiv time independently of browser timezone', () => {
    assert.equal(D.localTime(new Date('2026-10-23T21:24:00Z'), 'Europe/Kyiv'), '2026-10-24T00:24');
    assert.equal(D.localTime(new Date('2026-12-23T22:24:00Z'), 'Europe/Kyiv'), '2026-12-24T00:24');
});
