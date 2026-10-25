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
