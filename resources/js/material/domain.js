/* Independent presentation helpers shared by the Vue app and its tests. */
(function (scope) {
    'use strict';
    const money = value => new Intl.NumberFormat('uk-UA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value) || 0);
    const dateKey = value => `${value.getFullYear()}-${String(value.getMonth() + 1).padStart(2, '0')}-${String(value.getDate()).padStart(2, '0')}`;
    const localTime = (value, timezone) => {
        if (!timezone) return `${dateKey(value)}T${String(value.getHours()).padStart(2, '0')}:${String(value.getMinutes()).padStart(2, '0')}`;
        const parts = new Intl.DateTimeFormat('en-GB', { timeZone: timezone, year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hourCycle: 'h23' }).formatToParts(value);
        const fields = Object.fromEntries(parts.map(part => [part.type, part.value]));
        return `${fields.year}-${fields.month}-${fields.day}T${fields.hour}:${fields.minute}`;
    };
    function calendar(cursor) {
        const start = new Date(cursor.getFullYear(), cursor.getMonth(), 1, 12);
        start.setDate(start.getDate() - (start.getDay() + 6) % 7);
        return Array.from({ length: 42 }, (_, i) => {
            const day = new Date(start); day.setDate(start.getDate() + i);
            return { key: dateKey(day), day: day.getDate(), outside: day.getMonth() !== cursor.getMonth() };
        });
    }
    function route(path, base = '') {
        const prefix = new URL(base || '/', 'http://localhost').pathname.replace(/\/$/, '');
        const relative = path.startsWith(prefix + '/') ? path.slice(prefix.length) : path;
        const match = relative.match(/^\/(year|month)\/(\d+)\/?$/);
        if (match) return { name: match[1] === 'year' ? 'year' : 'month', id: Number(match[2]) };
        if (/^\/tasks(?:\/[^/]+)?\/?$/.test(relative)) return { name: 'tasks', id: null };
        return { name: ({ '/users': 'users', '/categories': 'categories', '/transactions': 'transactions', '/exchange': 'exchange', '/login': 'login', '/forgot-password': 'forgot' })[relative.replace(/\/$/, '')] || (relative.startsWith('/reset-password/') ? 'reset' : 'home'), id: null };
    }
    function totals(periods) { return periods.reduce((acc, row) => ({ budget: acc.budget + Number(row.budget), spent: acc.spent + Number(row.spent) }), { budget: 0, spent: 0 }); }
    function progress(row) { return row.budget > 0 ? Math.max(0, Math.min(100, row.spent / row.budget * 100)) : row.spent > 0 ? 100 : 0; }
    function convert(amount, from, rates) {
        const cnyRate = Number(rates.cny_usdt), uahRate = Number(rates.usdt_uah), value = Number(amount);
        if (!Number.isFinite(value) || !Number.isFinite(cnyRate) || !Number.isFinite(uahRate) || cnyRate <= 0 || uahRate <= 0) return null;
        const usd = from === 'cny' ? value * cnyRate : from === 'uah' ? value / uahRate : value;
        return { cny: usd / cnyRate, usd, uah: usd * uahRate };
    }
    scope.FinanceDomain = { money, dateKey, localTime, calendar, route, totals, progress, convert };
})(globalThis);
