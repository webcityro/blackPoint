<template>
	<admin-layout
		page-title="Users"
		:breadcrumbs="{
			Users: route('admin.users.index'),
		}"
	>
		<template #subheader>
			<li class="c-header-nav-item">
				<button
					type="button"
					class="btn btn-primary"
					@click="setDisplayForm({ group, show: true })"
				>
					<i class="fas fa-user-plus mr-2"></i> Add new user
				</button>
			</li>
		</template>

		<users-records :group="group" :roles="roles" />

		<user-form :group="group" :roles="roles" />
	</admin-layout>
</template>

<script>
import AdminLayout from '@/Layouts/Admin/AdminLayout';
import UsersRecords from "./UsersRecords";
import UserForm from "./UserForm";

export default {
	components: {
		AdminLayout,
		UsersRecords,
		UserForm
	},

	props: {
		roles: { type: Object, required: true }
	},

	data() {
		return {
			group: 'users'
		};
	}
}
</script>
