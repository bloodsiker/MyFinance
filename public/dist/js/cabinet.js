/* global Vue */
(() => {
    'use strict';
    const { createApp, ref, computed, onMounted, onUnmounted, nextTick } = Vue;
    const read = id => JSON.parse(document.getElementById(id).textContent);
    const money = value => new Intl.NumberFormat('uk-UA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value) || 0);
    const paths = {
        wallet: 'M3 7h18v13H3z M3 7V4h15v3 M16 12h5v4h-5z',
        grid: 'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        chart: 'M4 20V10 M10 20V4 M16 20v-8 M22 20H2',
        category: 'M3 3h7v7H3z M17.5 3L22 10h-9z M10 18a4 4 0 1 1-8 0 4 4 0 0 1 8 0 M14 14h7v7h-7z',
        task: 'M9 5H5v16h14V5h-4 M9 3h6v4H9z M8 13l3 3 5-5',
        calculator: 'M5 2h14v20H5z M8 5h8v4H8z M8 13h1 M15 13h1 M8 17h1 M15 17h1',
        arrow: 'M5 12h14 M14 7l5 5-5 5',
        down: 'M12 4v16 M5 13l7 7 7-7',
        plus: 'M12 5v14 M5 12h14',
        close: 'M6 6l12 12 M6 18L18 6',
        menu: 'M4 6h16 M4 12h16 M4 18h16',
        chevron: 'M9 5l7 7-7 7',
        logout: 'M9 4H4v16h5 M12 12h10 M17 7l5 5-5 5',
        search: 'M21 21l-5-5 M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
        check: 'M5 12l4 4L19 6',
        calendar: 'M3 5h18v16H3z M7 2v6 M17 2v6 M3 11h18',
    };
    const Icon = { props: ['name'], computed: { path() { return paths[this.name] || paths.grid; } }, template: '<svg class="md-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path :d="path" /></svg>' };
    const NavItem = {
        name: 'NavItem', components: { MdIcon: Icon }, props: ['item'],
        data() { return { expanded: this.item.active }; },
        computed: { icon() { return ({dashboard:'grid',categories:'category',tasks:'task',exchange:'calculator',transactions:'wallet'})[this.item.key] || 'grid'; } },
        template: `<li><a v-if="item.url" :href="item.url" :class="['md-nav-link', {active:item.active}]" :aria-current="item.active ? 'page' : null"><md-icon :name="icon"/><span>{{item.title === 'Dashboard' ? 'Огляд фінансів' : item.title}}</span></a><button v-else class="md-nav-link" @click="expanded = !expanded" :aria-expanded="expanded"><md-icon :name="icon"/><span>{{item.title}}</span><md-icon name="chevron"/></button><ul v-if="item.children.length && expanded" class="md-subnav"><nav-item v-for="child in item.children" :key="child.key" :item="child"/></ul></li>`,
    };
    if (document.getElementById('cabinet-shell')) {
        const config = read('cabinet-config');
        createApp({
            components: { MdIcon: Icon, NavItem },
            setup() {
                const open = ref(false), account = ref(false), drawer = ref(null), menuButton = ref(null);
                const flat = items => items.flatMap(item => [item, ...flat(item.children)]);
                const selected = flat(config.navigation).find(item => item.active);
                const titles = {'dashboard':'Огляд фінансів','month.list':'Річний бюджет','transactions.list':'Бюджет місяця','transactions.all':'Усі транзакції','category.list':'Категорії','task.list':'Задачі','task.list_by_date':'Задачі','exchange':'Калькулятор'};
                const title = titles[config.route] || selected?.title || 'Кабінет';
                const close = () => { open.value = false; nextTick(() => menuButton.value?.focus()); };
                const keydown = event => {
                    if (event.key === 'Escape') { account.value = false; if (open.value) close(); }
                    if (event.key === 'Tab' && open.value) {
                        const elements = [...drawer.value.querySelectorAll('a,button')].filter(el => el.offsetParent !== null);
                        const first = elements[0], last = elements[elements.length - 1];
                        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
                    }
                };
                const toggle = () => { open.value = !open.value; if(open.value) nextTick(() => drawer.value.querySelector('a,button')?.focus()); };
                onMounted(() => document.addEventListener('keydown', keydown));
                onUnmounted(() => document.removeEventListener('keydown', keydown));
                return { config, open, account, title, drawer, menuButton, close, toggle, initial: (config.name || config.email).charAt(0).toUpperCase() };
            },
            template: `<a class="md-skip" href="#main-content">Перейти до вмісту</a>
                <div v-if="open" class="md-scrim" @click="close"></div>
                <aside ref="drawer" id="cabinet-navigation" :class="['md-drawer',{ 'is-open':open }]" aria-label="Головна навігація">
                    <a :href="config.homeUrl" class="md-brand"><span class="md-brand-mark"><md-icon name="wallet"/></span><span>Finance<small>ОСОБИСТИЙ КАБІНЕТ</small></span></a>
                    <button class="md-icon-button md-drawer-close" @click="close" aria-label="Закрити меню"><md-icon name="close"/></button>
                    <p class="md-nav-label">ВАШ ПРОСТІР</p>
                    <nav><ul class="md-nav"><nav-item v-for="item in config.navigation" :key="item.key" :item="item"/><li v-if="!config.navigation.some(item => item.url === config.transactionsUrl)"><a :href="config.transactionsUrl" :class="['md-nav-link',{active:config.route === 'transactions.all'}]" :aria-current="config.route === 'transactions.all' ? 'page' : null"><md-icon name="wallet"/><span>Усі транзакції</span></a></li></ul></nav>
                    <div class="md-drawer-note"><span class="md-note-icon"><md-icon name="chart"/></span><strong>Плануйте впевнено</strong><p>Бюджети, витрати та щоденні справи — в одному місці.</p></div>
                    <a :href="config.logoutUrl" class="md-nav-link md-logout"><md-icon name="logout"/><span>Вийти з кабінету</span></a>
                </aside>
                <header class="md-topbar"><div class="md-topbar-start"><button ref="menuButton" class="md-icon-button md-menu-button" @click="toggle" :aria-expanded="open" aria-controls="cabinet-navigation" aria-label="Відкрити меню"><md-icon name="menu"/></button><span class="md-topbar-title">{{title}}</span></div><div class="md-account-wrap"><button class="md-account" @click="account = !account" :aria-expanded="account" aria-controls="account-panel"><span class="md-avatar">{{initial}}</span><span class="md-account-name">{{config.name}}</span><md-icon name="chevron"/></button><div v-if="account" id="account-panel" class="md-account-panel"><strong>{{config.name}}</strong><p>{{config.email}}</p><a :href="config.logoutUrl"><md-icon name="logout"/> Вийти</a></div><button v-if="account" class="md-account-dismiss" aria-label="Закрити профіль" @click="account = false"></button></div></header>`,
        }).mount('#cabinet-shell');
        document.body.classList.add('material-cabinet');
    }
    if (document.getElementById('finance-overview')) {
        const data = read('overview-data');
        createApp({
            components: { MdIcon: Icon },
            setup() {
                const query = ref(''), sort = ref('newest'), modal = ref(null), yearInput = ref(null), returnFocus = ref(null);
                const isMonths = Boolean(data.year);
                const periods = computed(() => {
                    const items = data.periods.filter(item => item.name.toLocaleLowerCase('uk').includes(query.value.toLocaleLowerCase('uk')));
                    return isMonths ? items : [...items].sort((a,b) => sort.value === 'newest' ? Number(b.name)-Number(a.name) : Number(a.name)-Number(b.name));
                });
                const budget = data.periods.reduce((sum,item) => sum + item.budget,0);
                const spent = data.periods.reduce((sum,item) => sum + item.spend,0);
                const percent = item => item.budget > 0 ? Math.min(100, Math.max(0, item.spend / item.budget * 100)) : item.spend > 0 ? 100 : 0;
                const openModal = event => { returnFocus.value = event.currentTarget; modal.value.showModal(); nextTick(() => yearInput.value.focus()); };
                const closeModal = () => modal.value.close();
                const restoreFocus = () => returnFocus.value?.focus();
                return { data, money, query, sort, periods, budget, spent, percent, isMonths, modal, yearInput, openModal, closeModal, restoreFocus, currentYear: new Date().getFullYear() };
            },
            template: `<div v-if="data.success" class="md-message" role="status"><md-icon name="check"/>{{data.success}}</div>
                <section class="md-page-heading"><div><p class="md-eyebrow">{{isMonths ? 'ПЛАНУВАННЯ БЮДЖЕТУ' : 'ВАША ФІНАНСОВА КАРТИНА'}}</p><h1>{{isMonths ? 'Бюджет на ' + data.year + ' рік' : 'Огляд фінансів'}}</h1><p class="md-subtitle">{{isMonths ? 'Оберіть місяць, щоб керувати бюджетом і витратами.' : 'Усе, що потрібно для впевненого управління коштами.'}}</p></div><button v-if="!isMonths" class="md-button md-filled" @click="openModal"><md-icon name="plus"/>Додати рік</button><a v-else :href="data.homeUrl" class="md-button md-outlined">До огляду<md-icon name="arrow"/></a></section>
                <section class="md-stat-grid" aria-label="Підсумок бюджету"><article class="md-stat md-stat-primary"><div class="md-stat-top"><span>{{isMonths ? 'Річний бюджет' : 'Загальний бюджет'}}</span><md-icon name="wallet"/></div><div class="md-stat-value">{{money(budget)}} <small>грн</small></div><p>{{isMonths ? 'Заплановано на ' + data.year + ' рік' : 'Заплановано за всі роки'}}</p></article><article class="md-stat"><div class="md-stat-top"><span>Загальні витрати</span><span class="md-stat-icon peach"><md-icon name="down"/></span></div><div class="md-stat-value">{{money(spent)}} <small>грн</small></div><p>Фактичні витрати {{isMonths ? 'за рік' : 'за всі роки'}}</p></article><article class="md-stat"><div class="md-stat-top"><span>{{budget >= spent ? 'Залишок бюджету' : 'Перевищення бюджету'}}</span><span class="md-stat-icon mint"><md-icon name="chart"/></span></div><div :class="['md-stat-value', {'md-negative': spent > budget}]">{{money(Math.abs(budget-spent))}} <small>грн</small></div><p>{{budget >= spent ? 'Доступно в межах плану' : 'Витрати перевищують план'}}</p></article></section>
                <nav v-if="isMonths" class="md-year-tabs" aria-label="Оберіть рік"><a v-for="year in data.years" :key="year.url" :href="year.url" :class="{active:year.active}" :aria-current="year.active ? 'page' : null">{{year.name}}</a></nav>
                <div :class="['md-overview-grid',{'months-only':isMonths}]"><section><div class="md-section-heading"><div><h2>{{isMonths ? 'Бюджети за місяцями' : 'Бюджети за роками'}}</h2><p>{{data.periods.length}} {{isMonths ? 'місяців для планування' : 'періодів у вашому просторі'}}</p></div><label v-if="!isMonths" class="md-sort"><span class="sr-only">Сортувати роки</span><select v-model="sort"><option value="newest">Спочатку нові</option><option value="oldest">Спочатку старі</option></select></label></div>
                <label v-if="data.periods.length > 0" class="md-search"><md-icon name="search"/><input v-model="query" type="search" :placeholder="isMonths ? 'Знайти місяць' : 'Знайти рік'" :aria-label="isMonths ? 'Знайти місяць' : 'Знайти рік'"></label>
                <div class="md-period-grid"><a v-for="period in periods" :key="period.id" :href="period.url" :class="['md-period-card',{'over-budget':period.spend > period.budget}]"><div class="md-period-top"><span class="md-period-icon"><md-icon name="calendar"/></span><span v-if="period.spend > period.budget" class="md-chip md-chip-error">Перевищено</span><span v-else class="md-chip">У межах плану</span></div><h3>{{period.name}}</h3><span class="md-muted">Запланований бюджет</span><div class="md-period-amount">{{money(period.budget)}} <small>грн</small></div><div class="md-budget-label"><span>Витрачено</span><strong>{{money(period.spend)}} грн</strong></div><div class="md-progress" role="progressbar" :aria-label="'Використано бюджет: ' + period.name" :aria-valuenow="Math.round(percent(period))" aria-valuemin="0" aria-valuemax="100"><span :style="{width:percent(period)+'%'}"></span></div><div class="md-card-bottom"><span>{{period.budget >= period.spend ? 'Залишок ' : 'Перевищення '}}{{money(Math.abs(period.budget-period.spend))}} грн</span><md-icon name="arrow"/></div></a></div>
                <div v-if="!periods.length" class="md-empty"><md-icon name="calendar"/><h3>{{query ? 'Нічого не знайдено' : 'Почнімо планувати'}}</h3><p>{{query ? 'Спробуйте інший пошуковий запит.' : 'Додайте перший рік, щоб створити місячні бюджети.'}}</p><button v-if="query" class="md-button md-tonal" @click="query = ''">Скинути пошук</button><button v-else-if="!isMonths" class="md-button md-filled" @click="openModal">Додати рік</button></div></section>
                <aside v-if="!isMonths" class="md-recent"><div class="md-section-heading"><div><h2>Останні витрати</h2><p>Останні 10 транзакцій</p></div><span class="md-count">{{data.transactions.length}}</span></div><div class="md-recent-list"><article v-for="transaction in data.transactions" :key="transaction.id" class="md-transaction"><span class="md-transaction-icon"><md-icon name="wallet"/></span><div class="md-transaction-detail"><h3>{{transaction.title}}</h3><p>{{transaction.category}}</p><time>{{transaction.date}}</time></div><strong>−{{money(transaction.amount)}}<small>грн</small></strong></article><div v-if="!data.transactions.length" class="md-empty"><md-icon name="wallet"/><h3>Ще немає витрат</h3><p>Додайте транзакцію в бюджеті потрібного місяця.</p></div></div><a :href="data.allUrl" class="md-all-link">Усі транзакції<md-icon name="arrow"/></a></aside></div>
                <dialog v-if="!isMonths" ref="modal" class="md-dialog" @click="($event.target === modal) && closeModal()" @close="restoreFocus" aria-labelledby="year-dialog-title"><form :action="data.createUrl" method="POST"><input type="hidden" name="_token" :value="data.csrf"><span class="md-dialog-icon"><md-icon name="calendar"/></span><h2 id="year-dialog-title">Новий рік — нові плани</h2><p>Створимо 12 місяців для планування вашого бюджету.</p><label class="md-field" for="new-year">Рік<input ref="yearInput" id="new-year" name="year" type="number" min="1900" max="9999" step="1" :value="currentYear" required></label><div class="md-dialog-actions"><button class="md-button md-text" type="button" @click="closeModal">Скасувати</button><button class="md-button md-filled" type="submit">Створити рік</button></div></form></dialog>`,
        }).mount('#finance-overview');
    }
    if (document.getElementById('finance-categories')) {
        const data = read('categories-data');
        createApp({
            components: { MdIcon: Icon },
            setup() {
                const query = ref(''), modal = ref(null), nameInput = ref(null), returnFocus = ref(null);
                const items = computed(() => data.items.filter(item => item.name.toLocaleLowerCase('uk').includes(query.value.toLocaleLowerCase('uk'))));
                const open = event => { returnFocus.value = event.currentTarget; modal.value.showModal(); nextTick(() => nameInput.value.focus()); };
                return { data, query, modal, nameInput, returnFocus, items, open };
            },
            template: `<div v-if="data.success" class="md-message" role="status"><md-icon name="check"/>{{data.success}}</div><section class="md-page-heading"><div><p class="md-eyebrow">ОРГАНІЗАЦІЯ ВИТРАТ</p><h1>Категорії</h1><p class="md-subtitle">Зручний порядок у щоденних витратах.</p></div><button class="md-button md-filled" @click="open"><md-icon name="plus"/>Додати категорію</button></section><section class="md-category-surface"><div class="md-section-heading"><div><h2>Ваші категорії</h2><p>Усього: {{data.items.length}}</p></div></div><label class="md-search"><md-icon name="search"/><input v-model="query" type="search" placeholder="Знайти категорію" aria-label="Знайти категорію"></label><div class="md-category-list"><article v-for="item in items" :key="item.id" class="md-category-item"><span class="md-period-icon"><md-icon name="category"/></span><h3>{{item.name}}</h3></article></div><div v-if="!items.length" class="md-empty"><md-icon name="category"/><h3>{{query ? 'Нічого не знайдено' : 'Додайте першу категорію'}}</h3><p>{{query ? 'Спробуйте іншу назву.' : 'Наприклад: продукти, транспорт або відпочинок.'}}</p><button v-if="query" class="md-button md-tonal" @click="query = ''">Скинути пошук</button><button v-else class="md-button md-filled" @click="open">Додати категорію</button></div></section><dialog ref="modal" class="md-dialog" @click="($event.target === modal) && modal.close()" @close="returnFocus?.focus()" aria-labelledby="category-dialog-title"><form :action="data.createUrl" method="POST"><input type="hidden" name="_token" :value="data.csrf"><span class="md-dialog-icon"><md-icon name="category"/></span><h2 id="category-dialog-title">Нова категорія</h2><p>Назвіть категорію, щоб групувати свої витрати.</p><label class="md-field" for="new-category">Назва<input ref="nameInput" id="new-category" name="name" type="text" placeholder="Наприклад, продукти" required maxlength="255"></label><div class="md-dialog-actions"><button class="md-button md-text" type="button" @click="modal.close()">Скасувати</button><button class="md-button md-filled" type="submit">Додати</button></div></form></dialog>`,
        }).mount('#finance-categories');
    }
})();
