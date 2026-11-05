/* global Vue, FinanceDomain, FinanceOOP */
(() => {
    'use strict';
    const { createApp } = Vue;
    const D = FinanceDomain;
    const boot = JSON.parse(document.getElementById('finance-bootstrap').textContent);
    const { Icon, CustomSelect, Modal } = FinanceOOP.Components;
    const readTemplate = id => document.getElementById(id).innerHTML;
    const sharedComponents = { Icon, AppModal: Modal, CustomSelect };
    // v-stuck: adds .is-stuck while a position:sticky element is pinned to the top of the viewport.
    const stuck = {
        mounted(el) {
            let frame = 0;
            const update = () => {
                frame = 0;
                const pinned = getComputedStyle(el).position === 'sticky' && el.getBoundingClientRect().top <= 0.5
                    && el.parentElement.getBoundingClientRect().top < 0;
                el.classList.toggle('is-stuck', pinned);
            };
            el._stuckHandler = () => { if (!frame) frame = requestAnimationFrame(update); };
            addEventListener('scroll', el._stuckHandler, { passive: true });
            addEventListener('resize', el._stuckHandler);
            update();
        },
        updated(el) { el._stuckHandler?.(); },
        unmounted(el) {
            removeEventListener('scroll', el._stuckHandler);
            removeEventListener('resize', el._stuckHandler);
        },
    };
    const injectedPage = id => ({
        components: sharedComponents,
        directives: { stuck },
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
