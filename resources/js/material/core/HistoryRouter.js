(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class HistoryRouter {
    constructor({ baseUrl, domain, locationObject = location, historyObject = history }) {
        this.baseUrl = baseUrl;
        this.domain = domain;
        this.location = locationObject;
        this.history = historyObject;
    }

    url(path) { return this.baseUrl.replace(/\/$/, '') + path; }

    currentRoute() { return this.domain.route(this.location.pathname, this.baseUrl); }

    push(path) { this.history.pushState({}, '', this.url(path)); }

    replaceQuery(values) {
        const params = new URLSearchParams();
        for (const [key, value] of Object.entries(values)) {
            if (value && !(key === 'page' && value === 1)) params.set(key, value);
        }
        this.history.replaceState({}, '', this.location.pathname + (params.size ? `?${params}` : ''));
    }

    filtersFromLocation(filters) {
        const params = new URLSearchParams(this.location.search);
        for (const field of ['title', 'category_id', 'user_id', 'type']) filters[field] = params.get(field) || '';
        filters.page = Math.max(1, Number(params.get('page')) || 1);
    }

    taskDate() {
        const match = this.location.pathname.match(/\/tasks\/(\d{4}-\d{2}-\d{2})/);
        const params = new URLSearchParams(this.location.search);
        return params.get('day') || match?.[1] || this.domain.dateKey(new Date());
    }
}

    OOP.HistoryRouter = HistoryRouter;
})(globalThis);
