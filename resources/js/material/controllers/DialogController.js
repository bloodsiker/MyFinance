(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class DialogController {
    constructor({ store, api, dashboard, toast, domain, bootstrap, currentMonth, currentYear }) {
        this.store = store;
        this.api = api;
        this.dashboard = dashboard;
        this.toast = toast;
        this.domain = domain;
        this.bootstrap = bootstrap;
        this.currentMonth = currentMonth;
        this.currentYear = currentYear;
        this.analyticsSequence = 0;
        this.analyticsAbortController = null;
    }

    open(kind, row = {}) {
        const { store } = this;
        for (const key of Object.keys(store.form)) delete store.form[key];
        store.formError.value = '';
        store.analytics.value = null;
        if (kind === 'year') Object.assign(store.form, { year: new Date().getFullYear() });
        if (kind === 'category') Object.assign(store.form, { name: '' }, row);
        if (kind === 'userTelegram') Object.assign(store.form, { id: row.id, name: row.name, telegram_id: row.telegram_id || '' });
        if (kind === 'transaction') Object.assign(store.form, {
            month_id: this.currentMonth()?.id,
            category_id: store.data.budgets[0]?.category_id || '',
            title: '', amount: '', type: 'card',
        }, row, { created_at: row.id ? row.created_at_local : this.domain.localTime(new Date(), this.bootstrap.timezone || 'UTC') });
        if (kind === 'budget') Object.assign(store.form, { month_id: this.currentMonth()?.id, category_id: '', budget: '' }, row);
        if (kind === 'task') Object.assign(store.form, {
            user_id: row.user_id || store.user.value.id, description: '', priority: 'middle',
            task_date: store.selectedDate.value, task_time: '',
        });
        if (kind === 'deleteTransaction' || kind === 'deleteTask') Object.assign(store.form, row);
        store.dialog.value = kind;
        if (kind === 'budget' && store.form.category_id) this.loadAnalytics();
    }

    close() {
        if (this.store.saving.value) return;
        this.store.dialog.value = null;
        this.analyticsAbortController?.abort();
        this.analyticsSequence++;
    }

    async save() {
        const { store } = this;
        if (store.saving.value) return;
        store.saving.value = true;
        store.formError.value = '';
        const form = store.form;
        const endpoints = {
            userTelegram: [`users/${form.id}/telegram`, 'PUT'],
            year: ['years', 'POST'],
            category: [form.id ? `categories/${form.id}` : 'categories', form.id ? 'PUT' : 'POST'],
            budget: ['budgets', 'POST'],
            transaction: [form.id ? `transactions/${form.id}` : 'transactions', form.id ? 'PUT' : 'POST'],
            task: ['tasks', 'POST'],
            deleteTransaction: [`transactions/${form.id}`, 'DELETE'],
            deleteTask: [`tasks/${form.id}`, 'DELETE'],
        };
        try {
            const [endpoint, method] = endpoints[store.dialog.value];
            const result = await this.api.request(endpoint, {
                method,
                body: method === 'DELETE' ? undefined : JSON.stringify(form),
            });
            store.dialog.value = null;
            this.toast.show(result.message);
            await this.dashboard.load();
        } catch (error) {
            store.formError.value = error.message;
        } finally {
            store.saving.value = false;
        }
    }

    async loadAnalytics() {
        const requestId = ++this.analyticsSequence;
        this.analyticsAbortController?.abort();
        this.store.analytics.value = null;
        if (this.store.dialog.value !== 'budget' || !this.store.form.category_id || !this.currentYear()) {
            this.store.analyticsLoading.value = false;
            return;
        }
        this.analyticsAbortController = new AbortController();
        this.store.analyticsLoading.value = true;
        try {
            const params = new URLSearchParams({ year_id: this.currentYear().id, category_id: this.store.form.category_id });
            const result = await this.api.request(`analytics?${params}`, { signal: this.analyticsAbortController.signal });
            if (requestId === this.analyticsSequence) this.store.analytics.value = result.amount;
        } catch (error) {
            if (error.name !== 'AbortError' && requestId === this.analyticsSequence) {
                this.store.formError.value = 'Не вдалося завантажити рекомендацію. Бюджет можна ввести вручну.';
            }
        } finally {
            if (requestId === this.analyticsSequence) this.store.analyticsLoading.value = false;
        }
    }

    dispose() { this.analyticsAbortController?.abort(); }
}

    OOP.DialogController = DialogController;
})(globalThis);
