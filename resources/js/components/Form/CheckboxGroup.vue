<template>
	<div>
		<Checkbox
			v-if="showSelectAll"
			:id="id + '_select_all'"
			:label="selectAllLabel"
			v-model="selectAll"
			:checkedValue="true"
			:uncheckedValue="false"
			:indeterminate="indeterminate"
			@update:modelValue="updateSelectAll"
		/>

		<div
			class="container"
			:class="{
				'border-top': showSelectAll,
				'pt-2': showSelectAll,
				'is-invalid': isError,
			}"
		>
			<div class="row">
				<Checkbox
					v-for="(label, value) in options"
					:key="value"
					:id="id + '_' + value"
					:label="label"
					wrapper-css-class="custom-control custom-checkbox col-4"
					:checked="selected.includes(value)"
					:error="isError"
					v-bind="additionalListeners"
					@update:modelValue="update(value)"
				/>
			</div>
		</div>
	</div>
</template>

<script>
import CheckboxBase from "../../mixins/CheckboxBase";

export default {
	mixins: [CheckboxBase],

	data() {
		return {
			selectAll: false,
			indeterminate: false,
		};
	},

	mounted() {
		this.setSelectAll();
	},

	methods: {
		update(value) {
			if (this.selected.includes(value)) {
				this.selected = this.selected.filter(v => v !== value);
			} else {
				this.selected.push(value);
			}

			this.setSelectAll();
			this.emit();
		},

		setSelectAll() {
			this.indeterminate = this.selected.length > 0 && this.selected.length < Object.keys(this.options).length;
			this.selectAll = this.selected.length == Object.keys(this.options).length;
		},

		updateSelectAll() {
			this.selected = this.selectAll ? Object.keys(this.options) : [];
			this.emit();
		}
	}
};
</script>
