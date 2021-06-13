<script>
import Helper from "../core/Helper";
import FormInputBase from "./FormInputBase";
import HasValidator from "./HasValidator";
import { mapActions, mapGetters } from "vuex";

export default {
	mixins: [HasValidator, FormInputBase],

	props: {
		group: { type: String, required: false },
		label: { type: String, required: false },
		feedback: { type: String, required: false },
		whapperCssClass: { type: String, required: false, default: 'form-group' },
		whapperErrorCssClass: { type: String, required: false, default: '' },
		whapperValidCssClass: { type: String, required: false, default: '' },
		labelCssClass: { type: String, required: false, default: '' },
		labelErrorCssClass: { type: String, required: false, default: '' },
		labelValidCssClass: { type: String, required: false, default: '' },
		inputErrorCssClass: { type: String, required: false, default: 'is-invalid' },
		inputValidCssClass: { type: String, required: false, default: 'is-valid' },
		feedbackCssClass: { type: String, required: false, default: 'form-text' },
		validFeedbackCssClass: { type: String, required: false, default: 'valid-feedback' },
		invalidFeedbackCssClass: { type: String, required: false, default: 'invalid-feedback' },
		rules: { type: Object, required: false },
		validateOn: { type: [String, Array], required: false, default: 'submit' }
	},

	mounted() {
		this.init();
	},

	unmounted() {
		this.removeRules({
			group: this.group,
			field: this.id
		});
	},

	methods: {
		...mapActions('Form', ['setRules', 'removeRules']),

		init() {
			if (this.group && !Helper.isEmpty(this.validation)) {
				this.setRules({
					group: this.group,
					rules: {
						[this.id]: this.validation
					}
				});
			}
		},

		selfValidation() {
			this.validator.reset(this.id).validate(this.id)
				.then(() => {
					this.setErrors({ group: this.group, errors: this.validator.errors });
				})
				.catch(() => {
					this.setErrors({ group: this.group, errors: this.validator.errors });
				});
		}
	},

	computed: {
		...mapGetters('Form', ['getForm']),

		form() {
			return this.getForm[this.group];
		},

		validation() {
			return this.rules
		},

		validateOnListeners() {
			if (this.validateOn == 'submit') {
				return {};
			}

			if (Array.isArray(this.validateOn)) {
				const listeners = {};

				this.validateOn.forEach(event => listeners[this.listenerName(event)] = this.selfValidation);
				return listeners;
			}

			return {
				[this.listenerName(this.validateOn)]: this.selfValidation
			};
		},

		additionalListeners() {
			return {
				...this.validateOnListeners
			};
		},

		computedCssClasses() {
			return (defaultClass, errorClass, validClass) => [
				defaultClass,
				{
					[errorClass]: this.hasError(this.id),
					[validClass]: this.form.validFields.includes(this.id)
				}
			];
		},

		computedWrapperCssClasses() {
			return this.computedCssClasses(
				this.whapperCssClass,
				this.whapperErrorCssClass,
				this.whapperValidCssClass
			);
		},

		computedLabelCssClasses() {
			return this.computedCssClasses(
				this.labelCssClass,
				this.labelErrorCssClass,
				this.labelValidCssClass
			);
		},

		computedInputCssClasses() {
			return this.computedCssClasses(
				this.inputCssClass,
				this.inputErrorCssClass,
				this.inputValidCssClass
			);
		},

		computedFeedbackCssClasses() {
			return this.computedCssClasses(
				this.hasFeedback && !this.hasError ? this.feedbackCssClass : '',
				this.invalidFeedbackCssClass,
				this.validFeedbackCssClass
			);
		},

		hasFeedback() {
			return this.feedback || this.hasError;
		},

		getFeedback() {
			return this.hasError(this.id) ? this.getError(this.id) : this.feedback;
		}
	}
}
</script>
