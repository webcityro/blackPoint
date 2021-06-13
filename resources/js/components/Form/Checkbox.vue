<template>
	<div :class="wrapperCssClass">
		<input
			type="checkbox"
			:id="id"
			:class="computedCssClass"
			:value="modelValue"
			:checked="isChecked"
			v-bind="$attrs"
			@change="update"
		/>
		<label :for="id" :class="labelCssClass">{{ label }}</label>
	</div>
</template>

<script>
import CheckboxBase from "../../mixins/CheckboxBase";

export default {
	mixins: [CheckboxBase],

	props: {
		label: { type: String, required: true },
		checkedValue: { required: false },
		uncheckedValue: { required: false },
		wrapperCssClass: { type: String, required: false, default: 'custom-control custom-checkbox' },
		inputCssClass: { type: String, required: false, default: 'custom-control-input' },
		labelCssClass: { type: String, required: false, default: 'custom-control-label' },
		errorCssClass: { type: String, required: false, default: 'is-invalid' },
		error: { type: Boolean, required: false, default: false }
	},

	methods: {
		update(e) {
			this.emit(e.target.checked ? this.checkedValue : this.uncheckedValue);
		}
	},

	computed: {
		isChecked() {
			return this.modelValue == this.checkedValue;
		},

		computedCssClass() {
			return [...this.inputCssClass.split(' '), {
				[this.errorCssClass]: this.error
			}];
		}
	}
}
</script>

<style>
</style>
