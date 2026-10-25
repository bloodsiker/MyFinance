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
