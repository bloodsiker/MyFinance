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
