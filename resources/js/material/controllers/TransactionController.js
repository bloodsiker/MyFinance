(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class TransactionController {
    constructor({ store, router, dashboard }) {
        this.store = store;
        this.router = router;
        this.dashboard = dashboard;
        this.searchTimer = null;
    }

    applyFilters() {
        clearTimeout(this.searchTimer);
        this.store.filters.page = 1;
        this.router.replaceQuery(this.store.filters);
        return this.dashboard.load();
    }

    scheduleSearch() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => this.applyFilters(), 1000);
    }

    filterBy(field, value) {
        if (!value) return;
        this.store.filters[field] = String(value);
        return this.applyFilters();
    }

    filterByType(row) { return this.filterBy('type', row.type === 'cache' ? 'cash' : row.type); }
    filterByCategory(row) { return this.filterBy('category_id', row.category_id); }
    filterByUser(row) { return this.filterBy('user_id', row.user_id); }

    resetFilters() {
        clearTimeout(this.searchTimer);
        Object.assign(this.store.filters, { title: '', category_id: '', user_id: '', type: '', page: 1 });
        this.router.replaceQuery(this.store.filters);
        return this.dashboard.load();
    }

    changePage(delta) {
        this.store.filters.page += delta;
        this.router.replaceQuery(this.store.filters);
        return this.dashboard.load();
    }

    cancelSearch() { clearTimeout(this.searchTimer); }
    dispose() { this.cancelSearch(); }
}

    OOP.TransactionController = TransactionController;
})(globalThis);
