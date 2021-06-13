<script>
import FormInputBase from "./FormInputBase";

export default {
	mixins: [FormInputBase],

	emits: ['update:modelValue', 'change'],

	props: {
		options: { type: Object, required: false },
		modelValue: { required: false, default: [] },
		selectAllLabel: { type: String, required: false, default: "Select all" },
		showSelectAll: { type: Boolean, required: false, default: true }
	},

	data() {
		return {
			selected: []
		};
	},

	methods: {
		emit(value = this.selected) {
			this.$emit('update:modelValue', value);
			this.$emit('change', value);
		}
	},

	computed: {
		isError() {
			return this.$parent?.hasError?.(this.id) || false;
		}
	},

	watch: {
		modelValue: {
			handler(value) {
				this.selected = value;
			},
			immediate: true
		}
	}
};
</script>
