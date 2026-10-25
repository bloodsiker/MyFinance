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
