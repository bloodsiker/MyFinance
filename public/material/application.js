(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class ApiClient {
    constructor({ baseUrl, csrfToken, fetcher = (...args) => globalThis.fetch(...args) }) {
        this.baseUrl = baseUrl;
        this.csrfToken = csrfToken;
        this.fetcher = fetcher;
    }

    async request(path, options = {}) {
        const endpoint = path.startsWith('http') ? path : `${this.baseUrl}/${path}`;
        const response = await this.fetcher(endpoint, {
            credentials: 'same-origin',
            ...options,
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                ...options.headers,
            },
        });
        let body;
        try { body = await response.json(); } catch { body = {}; }
        if (!response.ok) throw new Error(this.errorMessage(response.status, body));
        return body;
    }

    errorMessage(status, body) {
        if (status === 419) return 'Сесія завершилась. Оновіть сторінку та увійдіть знову.';
        if (status === 401) return 'Потрібно увійти в кабінет.';
        return Object.values(body.errors || {}).flat().join(' ')
            || body.message
            || 'Не вдалося виконати дію. Спробуйте ще раз.';
    }
}

    OOP.ApiClient = ApiClient;
})(globalThis);

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

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class ToastService {
    constructor(notice) { this.notice = notice; this.timer = null; }

    show(message) {
        this.notice.value = message;
        clearTimeout(this.timer);
        this.timer = setTimeout(() => { this.notice.value = ''; }, 5000);
    }

    dispose() { clearTimeout(this.timer); }
}

    OOP.ToastService = ToastService;
})(globalThis);

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

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class ExchangeController {
    constructor({ store, api, domain }) { this.store = store; this.api = api; this.domain = domain; }

    async loadRates() {
        const { store } = this;
        if (store.rateBusy.value) return;
        store.rateBusy.value = true;
        store.rateError.value = '';
        try {
            Object.assign(store.rates, await this.api.request('rates'));
            this.recalculate(store.exchangeSource.value);
        } catch (error) { store.rateError.value = error.message; }
        finally { store.rateBusy.value = false; }
    }

    recalculate(source) {
        const { store } = this;
        store.exchangeSource.value = source;
        const result = this.domain.convert(store.exchange[source], source, store.rates);
        if (result) {
            for (const key of ['cny', 'usd', 'uah']) {
                if (key !== source) store.exchange[key] = Number(result[key].toFixed(2));
            }
        }
    }
}

    OOP.ExchangeController = ExchangeController;
})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class AuthController {
    constructor({ store, api, router, bootstrap }) {
        this.store = store;
        this.api = api;
        this.router = router;
        this.bootstrap = bootstrap;
    }

    async logout() {
        if (this.store.saving.value) return;
        this.store.saving.value = true;
        try {
            const result = await this.api.request('logout', { method: 'POST' });
            location.assign(result.url);
        } catch (error) {
            this.store.error.value = error.message;
            this.store.saving.value = false;
        }
    }

    async submit() {
        const { store } = this;
        if (store.saving.value) return;
        store.saving.value = true;
        store.authError.value = '';
        store.authMessage.value = '';
        const mode = store.view.value.name === 'forgot' ? 'forgot' : store.view.value.name === 'reset' ? 'reset' : 'login';
        const path = mode === 'forgot' ? '/forgot-password' : mode === 'reset' ? '/reset-password' : '/login';
        try {
            const result = await this.api.request(this.router.url(path), {
                method: 'POST',
                body: JSON.stringify({ ...store.auth, token: this.bootstrap.resetToken }),
            });
            if (mode === 'login') location.assign(result.url || this.router.url('/'));
            else if (mode === 'reset') {
                store.authMessage.value = result.message;
                setTimeout(() => location.assign(this.router.url('/login')), 1200);
            } else store.authMessage.value = result.message;
        } catch (error) { store.authError.value = error.message; }
        finally { store.saving.value = false; }
    }
}

    OOP.AuthController = AuthController;
})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class TelegramController {
    constructor({ store, api, toast }) { this.store = store; this.api = api; this.toast = toast; }

    async open(member = this.store.user.value) {
        if (this.store.linking.value) return;
        this.store.linking.value = true;
        this.store.error.value = '';
        const popup = window.open('', '_blank');
        if (popup) popup.opener = null;
        try {
            const result = await this.api.request(`users/${member.id}/telegram-link`, { method: 'POST' });
            if (popup) popup.location.replace(result.url);
            else window.location.assign(result.url);
        } catch (error) {
            popup?.close();
            this.store.error.value = error.message;
        } finally {
            this.store.linking.value = false;
            this.store.account.value = false;
        }
    }

    async toggleNotifications(member, event) {
        const enabled = event.target.checked;
        this.store.notificationSaving[member.id] = true;
        try {
            const result = await this.api.request(`users/${member.id}/telegram-notifications`, {
                method: 'PATCH', body: JSON.stringify({ enabled }),
            });
            member.telegram_notifications_enabled = result.enabled;
            this.toast.show(result.message);
        } catch (error) {
            event.target.checked = !enabled;
            this.store.error.value = error.message;
        } finally { delete this.store.notificationSaving[member.id]; }
    }
}

    OOP.TelegramController = TelegramController;
})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};

class CabinetViewModel {
    constructor({ Vue, bootstrap, domain }) {
        this.Vue = Vue;
        this.bootstrap = bootstrap;
        this.domain = domain;
        this.store = new OOP.CabinetStore(Vue, bootstrap, domain);
        this.api = new OOP.ApiClient({ baseUrl: bootstrap.api, csrfToken: bootstrap.csrf });
        this.router = new OOP.HistoryRouter({ baseUrl: bootstrap.base, domain });
        this.toast = new OOP.ToastService(this.store.notice);
        this.titles = {
            home: 'Огляд фінансів', year: 'Річний бюджет', month: 'Бюджет місяця', transactions: 'Транзакції',
            categories: 'Категорії', users: 'Користувачі', tasks: 'Задачі', exchange: 'Калькулятор',
            login: 'Вхід', forgot: 'Відновлення пароля', reset: 'Новий пароль',
        };
        this.title = Vue.computed(() => this.titles[this.store.view.value.name] || 'Finance');
        this.isAuth = Vue.computed(() => !this.store.user.value || ['login', 'forgot', 'reset'].includes(this.store.view.value.name));
        this.currentMonth = Vue.computed(() => this.store.data.months.find(row => row.id === this.store.view.value.id));
        this.currentYear = Vue.computed(() => this.store.data.years.find(row => row.id === (
            this.store.view.value.name === 'year' ? this.store.view.value.id : this.currentMonth.value?.year_id
        )));
        this.months = Vue.computed(() => this.store.data.months.filter(row => row.year_id === this.currentYear.value?.id));
        this.periods = Vue.computed(() => {
            const rows = this.store.view.value.name === 'year' ? this.months.value : this.store.data.years;
            const filtered = rows.filter(row => String(row.name).toLocaleLowerCase('uk')
                .includes(this.store.search.value.toLocaleLowerCase('uk')));
            return this.store.view.value.name === 'year' ? filtered : [...filtered].sort((a, b) => (
                this.store.sort.value === 'desc' ? Number(b.name) - Number(a.name) : Number(a.name) - Number(b.name)
            ));
        });
        this.summary = Vue.computed(() => this.store.view.value.name === 'month'
            ? { budget: this.currentMonth.value?.budget || 0, spent: this.currentMonth.value?.spent || 0 }
            : this.store.view.value.name === 'year' ? domain.totals(this.months.value) : domain.totals(this.store.data.years));
        this.remaining = Vue.computed(() => this.summary.value.budget - this.summary.value.spent);
        this.categories = Vue.computed(() => this.store.data.categories.filter(row => row.name.toLocaleLowerCase('uk')
            .includes(this.store.categorySearch.value.toLocaleLowerCase('uk'))));
        this.members = Vue.computed(() => this.store.data.members.filter(member => (
            [member.name, member.email, member.telegram_id].filter(Boolean).join(' ').toLocaleLowerCase('uk')
                .includes(this.store.userSearch.value.toLocaleLowerCase('uk'))
        )));
        this.availableCategories = Vue.computed(() => this.store.dialog.value === 'budget' && !this.store.form.id
            ? this.store.data.categories.filter(row => !this.store.data.budgets.some(budget => budget.category_id === row.id))
            : this.store.data.categories);
        this.transactionCategories = Vue.computed(() => this.store.form.id ? this.store.data.categories
            : this.store.data.categories.filter(row => this.store.data.budgets.some(budget => budget.category_id === row.id)));
        this.calendar = Vue.computed(() => domain.calendar(this.store.cursor.value));
        this.calendarTitle = Vue.computed(() => new Intl.DateTimeFormat('uk-UA', { month: 'long', year: 'numeric' }).format(this.store.cursor.value));
        this.selectedDayTitle = Vue.computed(() => new Intl.DateTimeFormat('uk-UA', {
            day: 'numeric', month: 'long', year: 'numeric',
        }).format(new Date(`${this.store.selectedDate.value}T12:00:00`)));
        this.completed = Vue.computed(() => this.store.data.tasks.filter(row => row.is_completed).length);
        this.taskUsers = Vue.computed(() => this.store.data.users.filter(row => [2, 3].includes(row.id)
            || this.store.data.tasks.some(task => task.user_id === row.id) || row.id === this.store.user.value?.id));
        this.activeNav = Vue.computed(() => ['year', 'month'].includes(this.store.view.value.name) ? 'home' : this.store.view.value.name);
        this.dialogTitle = Vue.computed(() => ({
            year: 'Додати рік', userTelegram: 'Прив’язати Telegram ID',
            category: this.store.form.id ? 'Редагувати категорію' : 'Нова категорія',
            transaction: this.store.form.id ? 'Редагувати транзакцію' : 'Додати витрату',
            budget: this.store.form.id ? 'Змінити бюджет' : 'Додати бюджет', task: 'Нова задача',
            deleteTransaction: 'Видалити транзакцію?', deleteTask: 'Видалити задачу?',
        })[this.store.dialog.value] || '');
        this.dashboard = new OOP.DashboardController({ store: this.store, api: this.api, isAuthenticated: () => this.isAuth.value });
        this.transactions = new OOP.TransactionController({ store: this.store, router: this.router, dashboard: this.dashboard });
        this.dialogs = new OOP.DialogController({
            store: this.store, api: this.api, dashboard: this.dashboard, toast: this.toast, domain, bootstrap,
            currentMonth: () => this.currentMonth.value, currentYear: () => this.currentYear.value,
        });
        this.tasks = new OOP.TaskController({
            store: this.store, api: this.api, dashboard: this.dashboard,
            navigate: path => this.navigate(path), domain, toast: this.toast,
        });
        this.exchangeController = new OOP.ExchangeController({ store: this.store, api: this.api, domain });
        this.authController = new OOP.AuthController({ store: this.store, api: this.api, router: this.router, bootstrap });
        this.telegram = new OOP.TelegramController({ store: this.store, api: this.api, toast: this.toast });
        this.nav = [
            { name: 'home', path: '/', label: 'Огляд', icon: 'overview' },
            { name: 'transactions', path: '/transactions', label: 'Транзакції', icon: 'wallet' },
            { name: 'categories', path: '/categories', label: 'Категорії', icon: 'category' },
            { name: 'users', path: '/users', label: 'Користувачі', icon: 'users' },
            { name: 'tasks', path: '/tasks', label: 'Задачі', icon: 'tasks' },
            { name: 'exchange', path: '/exchange', label: 'Калькулятор', icon: 'exchange' },
        ];
        this.handlePopState = this.onPopState.bind(this);
        this.handleKeydown = this.onKeydown.bind(this);
        this.handleVisibility = this.refreshTelegramStatus.bind(this);
        this.stopDrawerWatch = Vue.watch(this.store.drawer, open => this.onDrawerChange(open));
        this.stopCategoryWatch = Vue.watch(() => this.store.form.category_id, () => this.onCategoryChange());
        Vue.onMounted(() => this.mount());
        Vue.onUnmounted(() => this.dispose());
    }

    url(path) { return this.router.url(path); }

    readLocation() {
        this.store.view.value = this.router.currentRoute();
        this.router.filtersFromLocation(this.store.filters);
        const day = this.router.taskDate();
        if (/^\d{4}-\d{2}-\d{2}$/.test(day) && !Number.isNaN(new Date(`${day}T12:00:00`).getTime())) {
            this.store.selectedDate.value = day;
            this.store.cursor.value = new Date(`${day}T12:00:00`);
        }
        document.title = `${this.titles[this.store.view.value.name] || 'Finance'} — Finance`;
    }

    async navigate(path) {
        if (this.store.saving.value) return;
        this.transactions.cancelSearch();
        this.store.ready.value = false;
        this.router.push(path);
        this.readLocation();
        this.store.drawer.value = false;
        this.store.account.value = false;
        this.store.search.value = '';
        this.store.error.value = '';
        window.scrollTo({ top: 0, behavior: 'instant' });
        await this.dashboard.load();
        if (this.store.view.value.name === 'exchange' && !this.store.rates.updated_at) await this.exchangeController.loadRates();
        this.Vue.nextTick(() => document.getElementById('page-heading')?.focus());
    }

    switchYear(selectedYearId) {
        const yearId = Number(selectedYearId);
        const target = this.store.data.months.find(month => Number(month.year_id) === yearId
            && Number(month.number) === Number(this.currentMonth.value?.number));
        if (target) this.navigate(`/month/${target.id}`);
        else this.store.error.value = 'В обраному році немає цього місяця.';
    }

    async load() { return this.dashboard.load(); }
    openDialog(kind, row) { return this.dialogs.open(kind, row); }
    closeDialog() { return this.dialogs.close(); }
    save() { return this.dialogs.save(); }
    loadAnalytics() { return this.dialogs.loadAnalytics(); }
    applyFilters() { return this.transactions.applyFilters(); }
    scheduleSearch() { return this.transactions.scheduleSearch(); }
    resetFilters() { return this.transactions.resetFilters(); }
    page(delta) { return this.transactions.changePage(delta); }
    filterByCategory(row) { return this.transactions.filterByCategory(row); }
    filterByUser(row) { return this.transactions.filterByUser(row); }
    filterByType(row) { return this.transactions.filterByType(row); }
    toggleTask(task) { return this.tasks.toggle(task); }
    selectDay(day) { return this.tasks.selectDay(day); }
    shiftCalendar(delta) { return this.tasks.shiftCalendar(delta); }
    tasksFor(id) { return this.tasks.tasksFor(id); }
    priorityLabel(value) { return this.tasks.priorityLabel(value); }
    loadRates() { return this.exchangeController.loadRates(); }
    recalculate(source) { return this.exchangeController.recalculate(source); }
    openTelegram(member) { return this.telegram.open(member); }
    toggleTelegramNotifications(member, event) { return this.telegram.toggleNotifications(member, event); }
    logout() { return this.authController.logout(); }
    submitAuth() { return this.authController.submit(); }

    async onPopState() {
        this.store.ready.value = false;
        this.readLocation();
        this.store.drawer.value = false;
        await this.dashboard.load();
        if (this.store.view.value.name === 'exchange' && !this.store.rates.updated_at) this.exchangeController.loadRates();
    }

    async onDrawerChange(open) {
        document.body.classList.toggle('drawer-open', open);
        await this.Vue.nextTick();
        if (open) this.store.drawerElement.value?.querySelector('a,button')?.focus();
        else this.store.menuElement.value?.focus();
    }

    onCategoryChange() {
        if (this.store.dialog.value === 'budget') this.dialogs.loadAnalytics();
    }

    refreshTelegramStatus() {
        if (!document.hidden && this.store.view.value.name === 'users' && !this.isAuth.value
            && !this.store.loading.value && !this.store.saving.value) this.dashboard.load();
    }

    onKeydown(event) {
        if (event.key === 'Escape') {
            this.store.drawer.value = false;
            this.store.account.value = false;
        }
        if (event.key === 'Tab' && this.store.drawer.value) {
            const nodes = [...this.store.drawerElement.value.querySelectorAll('a,button')]
                .filter(node => node.offsetParent !== null);
            const first = nodes[0], last = nodes[nodes.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    }

    mount() {
        this.readLocation();
        this.dashboard.load();
        if (!this.isAuth.value && this.store.view.value.name === 'exchange') this.exchangeController.loadRates();
        window.addEventListener('popstate', this.handlePopState);
        document.addEventListener('keydown', this.handleKeydown);
        document.addEventListener('visibilitychange', this.handleVisibility);
    }

    dispose() {
        this.dashboard.dispose();
        this.transactions.dispose();
        this.dialogs.dispose();
        this.toast.dispose();
        window.removeEventListener('popstate', this.handlePopState);
        document.removeEventListener('keydown', this.handleKeydown);
        document.removeEventListener('visibilitychange', this.handleVisibility);
        document.body.classList.remove('drawer-open');
        this.stopDrawerWatch();
        this.stopCategoryWatch();
    }

    bindings() {
        const store = this.store;
        return {
            boot: this.bootstrap, user: store.user, view: store.view, state: store.data,
            loading: store.loading, ready: store.ready, saving: store.saving, error: store.error,
            notice: store.notice, drawer: store.drawer, account: store.account, drawerEl: store.drawerElement,
            menuEl: store.menuElement, search: store.search, sort: store.sort, categorySearch: store.categorySearch,
            userSearch: store.userSearch, linking: store.linking, notificationsSaving: store.notificationSaving,
            filters: store.filters, selectedDate: store.selectedDate, cursor: store.cursor, dialog: store.dialog,
            form: store.form, formError: store.formError, analytics: store.analytics,
            analyticsLoading: store.analyticsLoading, auth: store.auth, authError: store.authError,
            authMessage: store.authMessage, rates: store.rates, rateBusy: store.rateBusy,
            rateError: store.rateError, exchange: store.exchange, nav: this.nav, title: this.title,
            isAuth: this.isAuth, activeNav: this.activeNav, currentMonth: this.currentMonth,
            currentYear: this.currentYear, months: this.months, periods: this.periods, summary: this.summary,
            remaining: this.remaining, categories: this.categories, members: this.members,
            availableCategories: this.availableCategories, transactionCategories: this.transactionCategories,
            calendar: this.calendar, calendarTitle: this.calendarTitle, selectedDayTitle: this.selectedDayTitle,
            completed: this.completed, taskUsers: this.taskUsers, dialogTitle: this.dialogTitle,
            url: this.url.bind(this), navigate: this.navigate.bind(this), switchYear: this.switchYear.bind(this),
            load: this.load.bind(this), applyFilters: this.applyFilters.bind(this),
            scheduleSearch: this.scheduleSearch.bind(this), filterByCategory: this.filterByCategory.bind(this),
            filterByUser: this.filterByUser.bind(this), filterByType: this.filterByType.bind(this),
            resetFilters: this.resetFilters.bind(this), page: this.page.bind(this),
            openDialog: this.openDialog.bind(this), closeDialog: this.closeDialog.bind(this), save: this.save.bind(this),
            toggleTask: this.toggleTask.bind(this), loadAnalytics: this.loadAnalytics.bind(this),
            selectDay: this.selectDay.bind(this), shiftCalendar: this.shiftCalendar.bind(this),
            tasksFor: this.tasksFor.bind(this), priorityLabel: this.priorityLabel.bind(this),
            loadRates: this.loadRates.bind(this), recalculate: this.recalculate.bind(this),
            openTelegram: this.openTelegram.bind(this), toggleTelegramNotifications: this.toggleTelegramNotifications.bind(this),
            logout: this.logout.bind(this), submitAuth: this.submitAuth.bind(this),
            money: this.domain.money, progress: this.domain.progress, today: this.domain.dateKey(new Date()),
            dateLabel: value => new Intl.DateTimeFormat('uk-UA', {
                timeZone: this.bootstrap.timezone || 'UTC', day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit',
            }).format(new Date(value)),
            initial: this.Vue.computed(() => (store.user.value?.name || store.user.value?.email || 'F').charAt(0).toUpperCase()),
        };
    }
}

    OOP.CabinetViewModel = CabinetViewModel;
})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};
    const Components = OOP.Components = OOP.Components || {};
    const Vue = global.Vue;
    const strokes = {
        users:'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M13 3a4 4 0 0 1 0 8 M22 21v-2a4 4 0 0 0-3-3.9 M13 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0',
        telegram:'M22 3L2 11l7 3 3 7 10-18 M9 14L22 3 M9 14l1 6 3-3',
        overview:'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        wallet:'M3 7h18v14H3z M3 7V3h15v4 M16 12h5v4h-5z',
        category:'M4 3h6v6H4z M17 3l4 7h-8z M7 14a4 4 0 1 1 0 8 4 4 0 0 1 0-8 M15 15h6v6h-6z',
        tasks:'M8 5H4v17h16V5h-4 M8 2h8v5H8z M8 14l3 3 6-7',
        exchange:'M4 7h15l-4-4 M20 17H5l4 4 M4 7l4 4 M20 17l-4-4',
        plus:'M12 4v16 M4 12h16', close:'M5 5l14 14 M5 19L19 5', menu:'M4 5h16 M4 12h16 M4 19h16',
        right:'M5 12h14 M13 6l6 6-6 6', left:'M19 12H5 M11 6l-6 6 6 6',
        search:'M16 16l5 5 M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
        down:'M12 3v18 M5 14l7 7 7-7', up:'M12 21V3 M5 10l7-7 7 7',
        calendar:'M3 5h18v16H3z M7 2v6 M17 2v6 M3 11h18',
        edit:'M14 4l6 6 M3 21l5-1L21 7l-5-5L3 15z', trash:'M3 6h18 M9 6V3h6v3 M5 6l1 15h12l1-15 M10 10v7 M14 10v7',
        check:'M4 12l5 5L20 5', logout:'M9 3H3v18h6 M11 12h11 M16 6l6 6-6 6',
        refresh:'M20 7a9 9 0 1 0 1 9 M20 2v6h-6', clock:'M12 7v5l4 3 M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0',
        arrow:'M4 17l6-6 4 4 6-9 M15 6h5v5', lock:'M5 10h14v11H5z M8 10V6a4 4 0 0 1 8 0v4',
    };
    class IconController {
        constructor(props) { this.shape = Vue.computed(() => strokes[props.name] || strokes.wallet); }
        bindings() { return { shape: this.shape }; }
    }
    const Icon = {
        props: ['name'],
        setup(props) { return new IconController(props).bindings(); },
        template: '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path :d="shape"/></svg>',
    };
    Components.Icon = Icon;

})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};
    const Components = OOP.Components = OOP.Components || {};
    const { ref, computed, nextTick, onMounted, onUnmounted } = global.Vue;
    const Icon = global.FinanceOOP.Components.Icon;
    class CustomSelectController {
        static sequence = 0;
        constructor(props, emit) {
            this.props = props;
            this.emit = emit;
            this.root = ref(null);
            this.trigger = ref(null);
            this.menu = ref(null);
            this.open = ref(false);
            this.activeIndex = ref(-1);
            this.listId = 'finance-select-' + (++CustomSelectController.sequence);
            this.selected = computed(() => props.options.find(option => String(option.value) === String(props.modelValue)) || null);
            this.outsideHandler = event => this.closeWhenOutside(event);
            onMounted(() => document.addEventListener('pointerdown', this.outsideHandler));
            onUnmounted(() => document.removeEventListener('pointerdown', this.outsideHandler));
        }
        focusOption() { nextTick(() => this.menu.value?.querySelector('[data-option-index="' + this.activeIndex.value + '"]')?.focus()); }
        show(index = this.props.options.findIndex(option => String(option.value) === String(this.props.modelValue))) {
            if (this.props.disabled) return;
            this.open.value = true;
            this.activeIndex.value = Math.max(0, index);
        }
        close(restore = false) {
            this.open.value = false;
            if (restore) nextTick(() => this.trigger.value?.focus());
        }
        choose(option) {
            if (this.props.disabled || option.disabled) return;
            this.emit('update:modelValue', option.value);
            this.emit('change', option.value);
            this.close(true);
        }
        move(delta) {
            if (!this.props.options.length) return;
            if (!this.open.value) { this.show(delta > 0 ? 0 : this.props.options.length - 1); return; }
            this.activeIndex.value = (this.activeIndex.value + delta + this.props.options.length) % this.props.options.length;
            this.focusOption();
        }
        onKeydown(event) {
            if (event.key === 'ArrowDown') { event.preventDefault(); this.move(1); }
            else if (event.key === 'ArrowUp') { event.preventDefault(); this.move(-1); }
            else if (event.key === 'Home' && this.open.value) { event.preventDefault(); this.activeIndex.value = 0; this.focusOption(); }
            else if (event.key === 'End' && this.open.value) { event.preventDefault(); this.activeIndex.value = this.props.options.length - 1; this.focusOption(); }
            else if ((event.key === 'Enter' || event.key === ' ') && this.open.value) { event.preventDefault(); const option = this.props.options[this.activeIndex.value]; if (option) this.choose(option); }
            else if (event.key === 'Escape' && this.open.value) { event.preventDefault(); this.close(true); }
            else if (event.key === 'Tab' && this.open.value) this.close();
        }
        closeWhenOutside(event) { if (this.open.value && !this.root.value?.contains(event.target)) this.close(); }
        bindings() {
            return {
                root: this.root, trigger: this.trigger, menu: this.menu, open: this.open,
                activeIndex: this.activeIndex, listId: this.listId, selected: this.selected,
                show: this.show.bind(this), close: this.close.bind(this), choose: this.choose.bind(this),
                onKeydown: this.onKeydown.bind(this),
            };
        }
    }
    const CustomSelect = {
        props: {
            modelValue: { default: '' }, options: { type: Array, default: () => [] }, disabled: Boolean,
            required: Boolean, ariaLabel: String, placeholder: { type: String, default: 'Оберіть значення' },
            variant: { type: String, default: 'default' },
        },
        emits: ['update:modelValue', 'change'], components: { Icon },
        setup(props, { emit }) { return new CustomSelectController(props, emit).bindings(); },
        template: `<div ref="root" :class="['custom-select',{open,disabled,plain:variant==='plain',pill:variant==='pill'}]" @keydown="onKeydown"><button ref="trigger" type="button" class="custom-select-trigger" role="combobox" :aria-label="ariaLabel" :aria-required="required" :aria-expanded="open" :aria-controls="listId" aria-haspopup="listbox" :disabled="disabled" @click="open?close():show()"><span :class="{placeholder:!selected}">{{selected?.label||placeholder}}</span><svg class="custom-select-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m5 7.5 5 5 5-5"/></svg></button><Transition name="select-pop"><div v-if="open" :id="listId" ref="menu" class="custom-select-menu" role="listbox" :aria-label="ariaLabel"><button v-for="(option,index) in options" :key="String(option.value)" :data-option-index="index" :id="listId+'-option-'+index" type="button" role="option" :aria-selected="String(option.value)===String(modelValue)" :disabled="option.disabled" :class="['custom-select-option',{selected:String(option.value)===String(modelValue)}]" @click="choose(option)"><span>{{option.label}}</span><icon v-if="String(option.value)===String(modelValue)" name="check"/></button><div v-if="!options.length" class="custom-select-empty">Немає доступних варіантів</div></div></Transition></div>`,
    };
    Components.CustomSelect = CustomSelect;

})(globalThis);

(function (global) {
    'use strict';
    const OOP = global.FinanceOOP = global.FinanceOOP || {};
    const Components = OOP.Components = OOP.Components || {};
    const { ref, watch, nextTick } = global.Vue;
    const Icon = global.FinanceOOP.Components.Icon;
    class ModalController {
        constructor(props, emit) {
            this.props = props;
            this.emit = emit;
            this.element = ref(null);
            this.previousElement = null;
            watch(() => props.open, value => this.onOpenChange(value));
        }
        async onOpenChange(value) {
            await nextTick();
            if (value) {
                this.previousElement = document.activeElement;
                this.element.value.showModal();
                (this.element.value.querySelector('button[role=combobox]:not(:disabled),input:not([type=hidden]):not(:disabled),select:not(:disabled),textarea:not(:disabled)')
                    || this.element.value.querySelector('button[type=submit]'))?.focus();
            } else if (this.element.value?.open) {
                this.element.value.close();
                this.previousElement?.focus();
            }
        }
        onKeydown(event) {
            if (event.key !== 'Enter' || event.isComposing || event.keyCode === 229) return;
            if (event.target.tagName === 'BUTTON' || (event.target.tagName === 'TEXTAREA' && event.shiftKey)) return;
            event.preventDefault();
            if (!this.props.busy && !event.repeat) event.currentTarget.requestSubmit();
        }
        dismiss() { if (!this.props.busy) this.emit('close'); }
        bindings() { return { element: this.element, onKeydown: this.onKeydown.bind(this), dismiss: this.dismiss.bind(this) }; }
    }
    const Modal = {
        props: ['open', 'title', 'busy', 'error', 'danger', 'confirmLabel'],
        emits: ['close', 'save'], components: { Icon },
        setup(props, { emit }) { return new ModalController(props, emit).bindings(); },
        template: `<dialog ref="element" class="dialog" @cancel.prevent="dismiss" @click="$event.target === element && dismiss()" aria-labelledby="dialog-title"><form @keydown="onKeydown" @submit.prevent="!busy && $emit('save')"><div class="dialog-heading"><span class="dialog-symbol"><icon :name="danger?'trash':'edit'"/></span><button type="button" class="icon-button" aria-label="Закрити діалог" :disabled="busy" @click="dismiss"><icon name="close"/></button></div><h2 id="dialog-title">{{title}}</h2><div v-if="error" role="alert" class="notice error">{{error}}</div><fieldset :disabled="busy"><slot/></fieldset><div class="dialog-actions"><button class="button text" type="button" :disabled="busy" @click="dismiss">Скасувати</button><button :class="['button',danger?'danger':'primary']" type="submit" :disabled="busy"><span v-if="busy" class="spinner small"></span>{{busy ? (danger ? 'Видалення…' : 'Збереження…') : (confirmLabel || 'Підтвердити')}}</button></div></form></dialog>`,
    };
    Components.Modal = Modal;

})(globalThis);
