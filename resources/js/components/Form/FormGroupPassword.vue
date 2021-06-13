<template>
	<div :class="computedWrapperCssClasses">
		<label :for="id" :class="computedLabelCssClasses">{{ label }}</label>

		<div class="input-group">
			<input v-bind="computedAttrs" :type="passwordType" />
			<div class="input-group-append">
				<div
					class="custom-control custom-checkbox input-group-text"
					style="padding-left: 2rem"
				>
					<input
						type="checkbox"
						class="custom-control-input"
						:id="checkboxId"
						@change="(e) => (showPassword = e.target.checked)"
					/>
					<label class="custom-control-label" :for="checkboxId">{{
						checkboxLabel
					}}</label>
				</div>
			</div>
			<div v-if="hasFeedback" :class="computedFeedbackCssClasses">
				{{ getFeedback }}
			</div>
		</div>
	</div>
</template>

<script>
import FormGroupBase from "../../mixins/FormGroupBase";

export default {
	mixins: [FormGroupBase],

	data() {
		return {
			passwordRules: {
				required: 'The Password is required.',
				password: 'Password must contents at least one uppercase letter, one lowercase letter and one number.',
				...this.rules
			},
			showPassword: false
		};
	},

	computed: {
		passwordType() {
			return this.showPassword ? 'text' : 'password';
		},

		checkboxLabel() {
			return this.showPassword ? 'Hide password' : 'Show password';
		},

		checkboxId() {
			return this.group + '_' + this.id + '_shpwPassword';
		},

		validation() {
			return this.passwordRules;
		},
	}
}
</script>
