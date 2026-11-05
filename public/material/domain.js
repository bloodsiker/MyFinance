/* Stateless domain rules shared by the application and its test harness. */
(function (scope) {
    'use strict';

    class FinanceDomain {
        static money(value) {
            return new Intl.NumberFormat('uk-UA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value) || 0);
        }

        static dateKey(value) {
            return `${value.getFullYear()}-${String(value.getMonth() + 1).padStart(2, '0')}-${String(value.getDate()).padStart(2, '0')}`;
        }

        static localTime(value, timezone) {
            if (!timezone) return `${this.dateKey(value)}T${String(value.getHours()).padStart(2, '0')}:${String(value.getMinutes()).padStart(2, '0')}`;
            const parts = new Intl.DateTimeFormat('en-GB', {
                timeZone: timezone, year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', hourCycle: 'h23',
            }).formatToParts(value);
            const fields = Object.fromEntries(parts.map(part => [part.type, part.value]));
            return `${fields.year}-${fields.month}-${fields.day}T${fields.hour}:${fields.minute}`;
        }

        static calendar(cursor) {
            const start = new Date(cursor.getFullYear(), cursor.getMonth(), 1, 12);
            start.setDate(start.getDate() - (start.getDay() + 6) % 7);
            return Array.from({ length: 42 }, (_, index) => {
                const day = new Date(start);
                day.setDate(start.getDate() + index);
                return { key: this.dateKey(day), day: day.getDate(), outside: day.getMonth() !== cursor.getMonth() };
            });
        }

        static route(path, base = '') {
            const prefix = new URL(base || '/', 'http://localhost').pathname.replace(/\/$/, '');
            const relative = path.startsWith(prefix + '/') ? path.slice(prefix.length) : path;
            const match = relative.match(/^\/(year|month)\/(\d+)\/?$/);
            if (match) return { name: match[1] === 'year' ? 'year' : 'month', id: Number(match[2]) };
            if (/^\/tasks(?:\/[^/]+)?\/?$/.test(relative)) return { name: 'tasks', id: null };
            return { name: ({ '/users': 'users', '/categories': 'categories', '/transactions': 'transactions', '/exchange': 'exchange', '/login': 'login', '/forgot-password': 'forgot' })[relative.replace(/\/$/, '')] || (relative.startsWith('/reset-password/') ? 'reset' : 'home'), id: null };
        }

        static totals(periods) {
            return periods.reduce((total, row) => ({
                budget: total.budget + Number(row.budget),
                spent: total.spent + Number(row.spent),
            }), { budget: 0, spent: 0 });
        }

        /** Spending share per category for a period card: segments sum to 100%, uncoloured categories get a stable soft tone. */
        static breakdown(row) {
            const fallback = ['#9db4d3', '#b7c6db', '#8aa1c1', '#c5d2e3', '#a9bcd6', '#7f97b8'];
            const items = (row?.categories || []).filter(item => Number(item.spent) > 0);
            const total = items.reduce((sum, item) => sum + Number(item.spent), 0);
            let offset = 0;
            return items.map((item, index) => {
                const percent = total > 0 ? Number(item.spent) / total * 100 : 0;
                const center = offset + percent / 2;
                offset += percent;
                return {
                    key: item.category_id ?? `none-${index}`,
                    name: item.name || 'Без категорії',
                    color: item.color || fallback[Math.abs(Number(item.category_id) || index) % fallback.length],
                    spent: Number(item.spent),
                    percent,
                    align: center < 22 ? 'start' : center > 78 ? 'end' : 'center',
                };
            });
        }

        static progress(row) {
            return row.budget > 0 ? Math.max(0, Math.min(100, row.spent / row.budget * 100)) : row.spent > 0 ? 100 : 0;
        }

        static convert(amount, from, rates) {
            const cnyRate = Number(rates.cny_usdt), uahRate = Number(rates.usdt_uah), value = Number(amount);
            if (!Number.isFinite(value) || !Number.isFinite(cnyRate) || !Number.isFinite(uahRate) || cnyRate <= 0 || uahRate <= 0) return null;
            const usd = from === 'cny' ? value * cnyRate : from === 'uah' ? value / uahRate : value;
            return { cny: usd / cnyRate, usd, uah: usd * uahRate };
        }
    }

    scope.FinanceDomain = FinanceDomain;
})(globalThis);
