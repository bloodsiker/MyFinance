(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class TaskController {
    constructor({ store, api, dashboard, navigate, domain, toast }) {
        this.store = store;
        this.api = api;
        this.dashboard = dashboard;
        this.navigate = navigate;
        this.domain = domain;
        this.toast = toast;
    }

    async toggle(task) {
        if (this.store.saving.value) return;
        this.store.saving.value = true;
        try {
            const result = await this.api.request(`tasks/${task.id}`, { method: 'PATCH' });
            this.toast?.show(result.message);
            await this.dashboard.load();
        } catch (error) {
            this.store.error.value = error.message;
        } finally {
            this.store.saving.value = false;
        }
    }

    selectDay(day) { return this.navigate(`/tasks/${day}?day=${day}`); }

    shiftCalendar(delta) {
        const next = new Date(this.store.cursor.value.getFullYear(), this.store.cursor.value.getMonth() + delta, 1, 12);
        return this.selectDay(this.domain.dateKey(next));
    }

    tasksFor(userId) { return this.store.data.tasks.filter(task => task.user_id === userId); }
    priorityLabel(value) { return ({ high: 'Високий', middle: 'Середній', low: 'Низький' })[value] || value; }
}

    OOP.TaskController = TaskController;
})(globalThis);
