(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class CabinetStore {
    constructor(Vue, bootstrap, domain) {
        this.user = Vue.ref(bootstrap.user);
        this.view = Vue.ref(domain.route(location.pathname, bootstrap.base));
        this.data = Vue.reactive({
            years: [], months: [], categories: [], users: [], members: [], budgets: [], tasks: [], recent: [],
            transactions: { items: [], page: 1, last_page: 1, total: 0, spent: 0 },
        });
        this.loading = Vue.ref(false);
        this.ready = Vue.ref(false);
        this.saving = Vue.ref(false);
        this.error = Vue.ref('');
        this.notice = Vue.ref('');
        this.drawer = Vue.ref(false);
        this.account = Vue.ref(false);
        this.drawerElement = Vue.ref(null);
        this.menuElement = Vue.ref(null);
        this.notificationSaving = Vue.reactive({});
        this.search = Vue.ref('');
        this.sort = Vue.ref('desc');
        this.categorySearch = Vue.ref('');
        this.userSearch = Vue.ref('');
        this.linking = Vue.ref(false);
        this.filters = Vue.reactive({ title: '', category_id: '', user_id: '', type: '', page: 1 });
        this.selectedDate = Vue.ref(domain.dateKey(new Date()));
        this.cursor = Vue.ref(new Date());
        this.dialog = Vue.ref(null);
        this.form = Vue.reactive({});
        this.formError = Vue.ref('');
        this.analytics = Vue.ref(null);
        this.analyticsLoading = Vue.ref(false);
        this.auth = Vue.reactive({ email: bootstrap.email || '', password: '', password_confirmation: '' });
        this.authError = Vue.ref('');
        this.authMessage = Vue.ref('');
        this.rates = Vue.reactive({ cny_usdt: '', usdt_cny: '', usdt_uah: '', updated_at: null });
        this.rateBusy = Vue.ref(false);
        this.rateError = Vue.ref('');
        this.exchange = Vue.reactive({ cny: 100, usd: 0, uah: 0 });
        this.exchangeSource = Vue.ref('cny');
    }
}

    OOP.CabinetStore = CabinetStore;
})(globalThis);
