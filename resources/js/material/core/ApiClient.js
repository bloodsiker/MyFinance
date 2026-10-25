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
