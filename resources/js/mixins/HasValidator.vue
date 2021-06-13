<script>
import { mapActions, mapGetters } from "vuex";

export default {
	methods: {
		...mapActions('Form', [
			'setWasValidated',
			'setBackendErrors',
			'setErrors',
			'setValidator',
			'setValidField',
			'removeValidField',
		]),

		clearErrors() {
			this.setErrors({ group: this.group, errors: {} });
		}
	},

	computed: {
		...mapGetters('Form', ['getRules', 'getForm']),

		validator() {
			return this.$store.getters['Form/getValidator'](this.group);
		},

		validationBag() {
			return this.getRules(this.group);
		},

		getAllErrors() {
			return this.$store.getters['Form/getAllErrors'](this.group);
		},

		hasError() {
			return field => !this.group ? false : this.$store.getters['Form/hasError'](this.group, field);
		},

		getError() {
			return field => this.$store.getters['Form/getError'](this.group, field);
		},

	}
}
</script>
