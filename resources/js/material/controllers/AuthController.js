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
