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
