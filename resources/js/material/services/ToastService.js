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
