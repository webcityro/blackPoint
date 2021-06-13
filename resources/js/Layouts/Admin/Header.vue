<template>
	<header
		class="c-header c-header-light c-header-fixed c-header-with-subheader"
	>
		<button
			class="c-header-toggler c-class-toggler d-lg-none mfe-auto"
			type="button"
			data-target="#sidebar"
			data-class="c-sidebar-show"
		>
			<svg class="c-icon c-icon-lg">
				<use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
			</svg>
		</button>
		<a class="c-header-brand d-lg-none" href="#">Creative Digital Agency - Admin</a>
		<button
			class="c-header-toggler c-class-toggler mfs-3 d-md-down-none"
			type="button"
			data-target="#sidebar"
			data-class="c-sidebar-lg-show"
			responsive="true"
		>
			<svg class="c-icon c-icon-lg">
				<use xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
			</svg>
		</button>
		<ul class="c-header-nav d-md-down-none">
			<li class="c-header-nav-item px-3">
				<h1>{{ pageTitle }}</h1>
			</li>
		</ul>
		<ul class="c-header-nav ml-auto mr-4">

			<li class="c-header-nav-item dropdown">
				<a
					class="c-header-nav-link"
					data-toggle="dropdown"
					href="#"
					role="button"
					aria-haspopup="true"
					aria-expanded="false"
				>
					<div
						class="c-avatar"
						v-if="$page.props.jetstream.managesProfilePhotos"
					>
						<img
							class="rounded-circle"
							width="32"
							height="32"
							:src="$page.props.user.profile_photo_url"
							:alt="$page.props.user.name"
						/>
					</div>
					<span v-else>
						{{ $page.props.user.name }}

						<svg
							class="ml-2"
							width="18"
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 20 20"
							fill="currentColor"
						>
							<path
								fill-rule="evenodd"
								d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
								clip-rule="evenodd"
							/>
						</svg>
					</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right pt-0">
					<div class="dropdown-header bg-light py-2">
						<strong>Account</strong>
					</div>
					<form @submit.prevent="logout">
						<button class="dropdown-item" type="submit">
							<svg class="c-icon mr-2">
								<use
									xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-account-logout"
								></use>
							</svg>
							Logout
						</button>
					</form>
				</div>
			</li>
		</ul>
		<div class="c-subheader px-3">
			<ol class="breadcrumb border-0 m-0">
				<li class="breadcrumb-item">Admin</li>
				<li
					class="breadcrumb-item"
					v-for="(url, label) in breadcrumbs"
					:key="url"
				>
					<a v-if="url" :href="url">{{ label }}</a>
					<template v-else>{{ label }}</template>
				</li>
			</ol>

			<ul v-if="$slots.subheader" class="c-header-nav ml-auto mr-4">
				<slot name="subheader"></slot>
			</ul>
		</div>
	</header>
</template>

<script>
export default {
	mixins: [],

	components: {},

	props: {
		pageTitle: { required: true, type: String },
		breadcrumbs: { required: true, type: Object }
	},

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

<style scoped>
.breadcrumb {
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	padding: 0.75rem 1rem;
	margin-bottom: 1.5rem;
	list-style: none;
	border-radius: 0;
	border-bottom: 1px solid;
	background-color: transparent;
	border-color: #d8dbe0;
}

.breadcrumb,
.breadcrumb-item {
	display: -ms-flexbox;
	display: flex;
}
</style>
