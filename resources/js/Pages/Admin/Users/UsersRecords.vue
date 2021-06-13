<template>
	<div>
		<!-- Search -->
		<larasearch-form
			:group="group"
			:url="route('admin.users.fetch')"
			:params="{
				search: {
					id: '',
					first_name: '',
					last_name: '',
					email: '',
					role_ids: [],
					active: '',
				},
			}"
			:inputs="{
				id: {
					label: 'ID',
					type: 'text',
				},
				first_name: {
					label: 'First name',
					type: 'text',
				},
				last_name: {
					label: 'Last name',
					type: 'text',
				},
				email: {
					label: 'Email',
					type: 'text',
				},
				role_ids: {
					label: 'Role',
				},
				active: {
					label: 'Active',
					type: 'select',
					options: {
						'': 'All',
						true: 'Active',
						false: 'Inactive',
					},
				},
			}"
			:order-by="{
				id: 'ID',
				first_name: 'First name',
				last_name: 'Last name',
				email: 'Email',
				is_admin: 'Admin',
				active: 'Active',
			}"
		>
			<template v-slot:role_ids="{ params, update }">
				<CheckboxGroup
					id="role_ids-search"
					:options="roles"
					:show-select-all="false"
					v-model="params.search.role_ids"
					@change="update('role_ids')"
				/>
			</template>
		</larasearch-form>
		<!-- /Search -->

		<!-- Records -->
		<larasearch-results
			:group="group"
			group-label="user"
			:record-name-column="['first_name', 'last_name']"
			v-slot="{ total, records, destroy, edit }"
		>
			<div class="card">
				<div class="card-header">
					List of users
					<p>Total number of users: {{ total }}</p>
				</div>
				<div class="card-body">
					<table
						v-if="records.length"
						class="table table-responsive-sm table-hover table-outline mb-0"
					>
						<thead class="thead-light">
							<tr>
								<th>ID</th>
								<th class="text-center">
									<svg class="c-icon">
										<use
											xlink:href="/vendors/@coreui/icons/svg/free.svg#cil-people"
										></use>
									</svg>
								</th>
								<th>First name</th>
								<th>Last name</th>
								<th>Email</th>
								<th>Roles</th>
								<th>Active</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in records" :key="user.id">
								<td>{{ user.id }}</td>
								<td class="text-center">
									<div class="c-avatar">
										<img
											class="c-avatar-img"
											:src="user.avatar"
											:alt="`${user.first_name} ${user.last_name} (${user.email})`"
										/><span class="c-avatar-status bg-success"></span>
									</div>
								</td>
								<td>{{ user.first_name }}</td>
								<td>{{ user.last_name }}</td>
								<td>{{ user.email }}</td>
								<td>{{ user.roles.map((r) => r.display_name).join(", ") }}</td>
								<td>{{ user.active ? "Active" : "Inactive" }}</td>
								<td class="text-right">
									<button
										type="button"
										class="btn btn-primary mr-2"
										@click="edit(user)"
									>
										<i class="fas fa-pencil-alt"></i> Edit
									</button>
									<button
										v-if="user.id != $page.props.user.id"
										type="button"
										class="btn btn-danger"
										@click="destroy(user)"
									>
										<i class="fas fa-trash"></i> Delete
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</larasearch-results>
		<!-- /Records -->

		<!-- Pagination -->
		<larasearch-pagination
			:group="group"
			always-show
			show-first-and-last
			align="center"
		></larasearch-pagination>
		<!-- /Pagination -->
	</div>
</template>

<script>
export default {
	props: {
		group: { required: true, type: String },
		roles: { type: Object, required: true },
	}
};
</script>
