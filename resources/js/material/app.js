/* global Vue, FinanceDomain, FinanceOOP */
(() => {
    'use strict';
    const { createApp } = Vue;
    const D = FinanceDomain;
    const boot = JSON.parse(document.getElementById('finance-bootstrap').textContent);
    const { Icon, CustomSelect, Modal } = FinanceOOP.Components;
    const readTemplate = id => document.getElementById(id).innerHTML;
    const sharedComponents = { Icon, AppModal: Modal, CustomSelect };
    const injectedPage = id => ({
        components: sharedComponents,
        setup() { return Vue.inject('finance-cabinet'); },
        template: readTemplate(id),
    });
    const AuthPage = {
        components: sharedComponents,
        setup() { return Vue.inject('finance-cabinet'); },
        template: readTemplate('finance-auth-template'),
    };
    const WorkspacePage = {
        components: {
            ...sharedComponents,
            OverviewPage: injectedPage('finance-overview-template'),
            TransactionsPage: injectedPage('finance-transactions-template'),
            UsersPage: injectedPage('finance-users-template'),
            CategoriesPage: injectedPage('finance-categories-template'),
            TasksPage: injectedPage('finance-tasks-template'),
            ExchangePage: injectedPage('finance-exchange-template'),
        },
        setup() { return Vue.inject('finance-cabinet'); },
        template: readTemplate('finance-workspace-template'),
    };
    const app = {
        components: { AuthPage, WorkspacePage },
        setup() {
            const viewModel = new FinanceOOP.CabinetViewModel({ Vue, bootstrap: boot, domain: D });
            const bindings = viewModel.bindings();
            Vue.provide('finance-cabinet', bindings);
            return bindings;
        },
        template: '<div><auth-page v-if="isAuth"/><workspace-page v-else/></div>',
    };
    createApp(app).mount('#finance-app');
})();
