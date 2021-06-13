<template>
	<div
		class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show"
		id="sidebar"
	>
		<div class="c-sidebar-brand d-lg-down-none">
			<h6>Black Point - Admin</h6>
		</div>
		<ul class="c-sidebar-nav ps">
			<li class="c-sidebar-nav-title">Users</li>
			<li class="c-sidebar-nav-item">
				<inertia-link
					class="c-sidebar-nav-link"
					:href="route('admin.users.index')"
				>
					<svg class="c-sidebar-nav-icon">
						<use
							xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-user"
						></use>
					</svg>
					Manage users
				</inertia-link>
			</li>
		</ul>
		<button
			class="c-sidebar-minimizer c-class-toggler"
			type="button"
			data-target="_parent"
			data-class="c-sidebar-minimized"
		></button>
	</div>
</template>

<script>
export default {
	mixins: [],

	components: {},

	props: {},

	data() {
		return {
		};
	},

	created() { },

	mounted() { },

	methods: {},

	computed: {},

	watch: {}
};
</script>

<style>
</style>
