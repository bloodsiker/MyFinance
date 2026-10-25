(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class DashboardController {
    constructor({ store, api, isAuthenticated }) {
        this.store = store;
        this.api = api;
        this.isAuthenticated = isAuthenticated;
        this.requestSequence = 0;
        this.abortController = null;
    }

    async load() {
        const { store } = this;
        if (this.isAuthenticated()) return;
        const requestId = ++this.requestSequence;
        this.abortController?.abort();
        this.abortController = new AbortController();
        store.loading.value = true;
        store.error.value = '';
        const params = new URLSearchParams();
        if (store.view.value.name === 'month') params.set('month_id', store.view.value.id);
        if (store.view.value.name === 'year') params.set('year_id', store.view.value.id);
        if (['month', 'transactions'].includes(store.view.value.name)) {
            for (const [key, value] of Object.entries(store.filters)) if (value) params.set(key, value);
        }
        params.set('date', store.selectedDate.value);

        try {
            const [dashboard, users] = await Promise.all([
                this.api.request(`data?${params}`, { signal: this.abortController.signal }),
                store.view.value.name === 'users'
                    ? this.api.request('users', { signal: this.abortController.signal })
                    : Promise.resolve(null),
            ]);
            if (requestId !== this.requestSequence) return;
            Object.assign(store.data, dashboard);
            if (users) store.data.members = users.users;
            store.ready.value = true;
        } catch (error) {
            if (error.name !== 'AbortError' && requestId === this.requestSequence) store.error.value = error.message;
        } finally {
            if (requestId === this.requestSequence) store.loading.value = false;
        }
    }

    dispose() { this.abortController?.abort(); }
}

    OOP.DashboardController = DashboardController;
})(globalThis);
