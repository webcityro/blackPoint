<script>
export default {
	props: {
		location: { type: String, required: false, default: 'top' },
	},

	computed: {
		show() {
			return !this.$page.props.jetstream.flash?.location
				|| this.$page.props.jetstream.flash?.location == this.location;
		},

		style() {
			return this.$page.props.jetstream.flash?.bannerStyle || 'success'
		},

		message() {
			return this.$page.props.jetstream.flash?.banner || ''
		},

		timeout() {
			return this.$page.props.jetstream.flash?.timeout || false;
		}
	},

	watch: {
		timeout(timeout) {
			if (timeout) {
				setTimeout(() => {
					this.$page.props.jetstream.flash.banner = '';
				}, timeout);
			}
		}
	}
}
</script>
