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
