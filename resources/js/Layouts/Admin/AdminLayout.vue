<template>
	<div class="c-app">
		<Sidebar />
		<div class="c-wrapper c-fixed-components">
			<AlertBanner />
			<Header :page-title="pageTitle" :breadcrumbs="breadcrumbs">
				<template v-if="$slots.subheader" #subheader>
					<slot name="subheader"></slot>
				</template>
			</Header>
			<!-- Page Content -->
			<div class="c-body">
				<main class="c-main px-sm-5">
					<slot></slot>
				</main>
				<footer class="c-footer">
					<div>&copy; <a href="#">Black Point</a></div>
					<div class="ml-auto">
						Powered by&nbsp;<a href="https://coreui.io/">CoreUI</a>
					</div>
				</footer>
			</div>
		</div>
	</div>
</template>

<script>
import BaseLayout from "../BaseLayout";
import AlertBanner from "./AlertBanner";
import Sidebar from "./Sidebar";
import Header from "./Header";

export default {
	mixins: [BaseLayout],

	components: {
		AlertBanner,
		Sidebar,
		Header
	},

	props: {
		pageTitle: { required: true, type: String },
		breadcrumbs: { required: true, type: Object }
	}
}
</script>
