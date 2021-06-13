<script>
export default {
	props: {
		id: { type: String, required: true },
		name: { type: String, required: false },
		modelValue: { required: false, default: '' },
		type: { type: String, required: false, default: 'text' },
		inputCssClass: { type: String, required: false, default: 'form-control' },
	},

	methods: {
		listenerName(listener) {
			return listener.substring(0, 2) == 'on'
				? listener
				: `on${listener.substring(0, 1).toUpperCase()}${listener.substring(1)}`;
		}
	},

	computed: {
		computedAttrs() {
			return {
				...this.$attrs,
				...this.additionalListeners,
				id: this.id,
				type: this.type,
				value: this.modelValue,
				class: this.computedInputCssClasses,
				name: this.computedName,
				onInput: event => {
					this.$emit('update:modelValue', event.target.value);
					if (typeof this.$attrs.onInput === 'function') this.$attrs.onInput(event);
					if (typeof this.additionalListeners.onInput === 'function') this.additionalListeners.onInput(event);
				}
			};
		},

		additionalListeners() {
			return {};
		},

		computedName() {
			return this.name || this.id;
		},

		computedInputCssClasses() {
			return this.inputCssClass;
		},
	}
}
</script>
