<template>
	<form-wrapper
		:url="route('admin.users.store')"
		:group="group"
		v-slot="{ group, fields, submit, cancel, processing, record, isEdit }"
		:fields="{
			first_name: '',
			last_name: '',
			email: '',
			role_ids: [],
			active: '',
		}"
		:createFields="{ password: '' }"
		:interceptRecord="changeForEdit"
		banner-location="modal"
	>
		<modal
			:title="
				isEdit
					? 'Your editing the user (' + record.name + ')'
					: 'Add a new user'
			"
			okButtonLabel="Save"
			:group="group"
			:processing="processing"
			@save="submit"
			@cancel="cancel"
		>
			<form-group
				id="first_name"
				label="First name:"
				:group="group"
				v-model="fields.first_name"
				:rules="{
					required: 'The First name is required.',
					'lengthRange:3,50':
						'The First name must contain between 3 and 50 characters.',
					alpha: 'The :attribute may only contain letters.',
				}"
				validate-on="input"
			/>

			<form-group
				id="last_name"
				label="Last name:"
				:group="group"
				v-model="fields.last_name"
				:rules="{
					required: 'The Last name is required.',
					'lengthRange:3,50':
						'The Last name must contain between 3 and 50 characters.',
					alpha: 'The :attribute may only contain letters.',
				}"
				validate-on="input"
			/>

			<form-group
				id="email"
				label="Email Address:"
				type="email"
				:group="group"
				v-model="fields.email"
				:rules="{
					required: 'The Email address is required.',
					email: 'The Email address must be a valid email.',
				}"
				validate-on="input"
			/>

			<form-group-password
				v-if="!isEdit"
				id="password"
				label="Password:"
				type="password"
				:group="group"
				v-model="fields.password"
				validate-on="input"
			/>

			<form-group-checkbox-group
				id="role_ids"
				label="User role:"
				:group="group"
				:options="roles"
				v-model="fields.role_ids"
				:rules="{
					required: 'The User role is required.',
				}"
				validate-on="change"
			/>

			<form-group
				id="active"
				label="Status:"
				:group="group"
				:rules="{
					required: 'The Status is required.',
				}"
				v-slot="{ inputClass, selfValidation }"
			>
				<select
					v-model="fields.active"
					id="active"
					:class="inputClass"
					@change="selfValidation"
				>
					<option :value="1">Active</option>
					<option :value="0">Inactive</option>
				</select>
			</form-group>
		</modal>
	</form-wrapper>
</template>

<script>
export default {
	props: {
		group: { type: String, required: true },
		roles: { type: Object, required: true },
	},

	methods: {
		changeForEdit(user) {
			user.role_ids = user.roles.map(role => role.id.toString());
			return Promise.resolve(user);
		}
	}
}
</script>
