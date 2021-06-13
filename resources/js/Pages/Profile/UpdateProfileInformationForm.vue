<template>
	<jet-form-section @submitted="updateProfileInformation">
		<template #title> Profile Information </template>

		<template #description>
			Update your account's profile information and email address.
		</template>

		<template #form>
			<jet-action-message :on="form.recentlySuccessful">
				Saved.
			</jet-action-message>

			<!-- Profile Photo -->
			<div class="form-group" v-if="$page.props.jetstream.managesProfilePhotos">
				<!-- Profile Photo File Input -->
				<input type="file" hidden ref="photo" @change="updatePhotoPreview" />

				<jet-label for="photo" value="Photo" />

				<!-- Current Profile Photo -->
				<div class="mt-2" v-show="!photoPreview">
					<img
						:src="user.profile_photo_url"
						alt="Current Profile Photo"
						class="rounded-circle"
					/>
				</div>

				<!-- New Profile Photo Preview -->
				<div class="mt-2" v-show="photoPreview">
					<img
						:src="photoPreview"
						class="rounded-circle"
						width="80px"
						height="80px"
					/>
				</div>

				<jet-secondary-button
					class="mt-2 mr-2"
					type="button"
					@click.prevent="selectNewPhoto"
				>
					Select A New Photo
				</jet-secondary-button>

				<jet-secondary-button
					type="button"
					class="mt-2"
					@click.prevent="deletePhoto"
					v-if="user.profile_photo_path"
				>
					Remove Photo
				</jet-secondary-button>

				<jet-input-error :message="form.errors.photo" class="mt-2" />
			</div>

			<div class="w-75">
				<!-- First Name -->
				<div class="form-group">
					<jet-label for="first_name" value="First name" />
					<jet-input
						id="first_name"
						type="text"
						v-model="form.first_name"
						:class="{ 'is-invalid': form.errors.first_name }"
						autocomplete="first_name"
					/>
					<jet-input-error :message="form.errors.first_name" />
				</div>

				<!-- Last Name -->
				<div class="form-group">
					<jet-label for="last_name" value="Last name" />
					<jet-input
						id="last_name"
						type="text"
						v-model="form.last_name"
						:class="{ 'is-invalid': form.errors.last_name }"
						autocomplete="last_name"
					/>
					<jet-input-error :message="form.errors.last_name" />
				</div>

				<!-- Email -->
				<div class="form-group">
					<jet-label for="email" value="Email" />
					<jet-input
						id="email"
						type="email"
						v-model="form.email"
						:class="{ 'is-invalid': form.errors.email }"
					/>
					<jet-input-error :message="form.errors.email" />
				</div>
			</div>
		</template>

		<template #actions>
			<jet-button
				:class="{ 'text-white-50': form.processing }"
				:disabled="form.processing"
			>
				Save
			</jet-button>
		</template>
	</jet-form-section>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
	components: {
		JetActionMessage,
		JetButton,
		JetFormSection,
		JetInput,
		JetInputError,
		JetLabel,
		JetSecondaryButton,
	},

	props: ['user'],

	data() {
		return {
			form: this.$inertia.form({
				_method: 'PUT',
				first_name: this.user.first_name,
				last_name: this.user.last_name,
				email: this.user.email,
				photo: null,
			}),

			photoPreview: null,
		}
	},

	methods: {
		updateProfileInformation() {
			if (this.$refs.photo) {
				this.form.photo = this.$refs.photo.files[0]
			}

			this.form.post(route('user-profile-information.update'), {
				errorBag: 'updateProfileInformation',
				preserveScroll: true
			});
		},

		selectNewPhoto() {
			this.$refs.photo.click();
		},

		updatePhotoPreview() {
			const reader = new FileReader();

			reader.onload = (e) => {
				this.photoPreview = e.target.result;
			};

			reader.readAsDataURL(this.$refs.photo.files[0]);
		},

		deletePhoto() {
			this.$inertia.delete(route('current-user-photo.destroy'), {
				preserveScroll: true,
				onSuccess: () => (this.photoPreview = null),
			});
		},
	},
}
</script>
