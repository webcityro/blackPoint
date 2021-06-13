<template>
	<form>
		<slot
			:group="group"
			:fields="formFields"
			:validate="validate"
			:submit="submit"
			:cancel="cancel"
			:reset="reset"
			:processing="processing"
			:record="editRecord"
			:isEdit="isEdit"
		></slot>
	</form>
</template>

<script>
import Helper from "@/core/Helper";
import ApiCaller from "@/core/ApiCaller";
import ErrorHandler from "@/core/ErrorHandler";
import HasValidator from "@/mixins/HasValidator";
import { mapActions, mapGetters } from "vuex";

export default {
	mixins: [HasValidator],

	props: {
		url: { type: String, required: true },
		group: { type: String, required: true },
		idColumn: { type: String, required: false, default: 'id' },
		record: { type: Object, required: false, default: null },
		interceptRecord: { required: false, default: () => records => Promise.resolve(records) },
		fields: { type: Object, required: true },
		createFields: { type: Object, required: false, default: () => { return {}; } },
		updateFields: { type: Object, required: false, default: () => { return {}; } },
		bannerLocation: { type: String, required: false, default: 'top' },
	},

	data() {
		return {
			formFields: {},
			editRecord: null,
			processing: false,
		};
	},

	created() {
		this.formFields = this.populateFields();
		this.formInit(this).then(() => {
			if (this.record) {
				return this.handleEdit(record);
			}

			this.reset(true);
		});
	},

	methods: {
		...mapActions('larasearch', ['goToFirstPageSortByIdDesc', 'replaceRecord']),
		...mapActions({
			formInit: 'Form/init',
			setEvent: 'Form/setEvent',
			setBackendErrors: 'Form/setBackendErrors',
			clearValidFields: 'Form/clearValidFields',
		}),

		populateFields(fields = { ...this.fields }, record = this.editRecord) {
			if (!record) {
				return { ...fields, ...this.createFields };
			}

			for (const field in { ...fields, ...this.updateFields }) {
				if (record.hasOwnProperty(field)) {
					fields[field] = Helper.isObject(fields[field]) ? this.populateFields(fields[field], record[field]) : record[field];
				}
			}
			return fields;
		},

		submit() {
			this.alert('');
			this.validate().then(this.save).catch(error => { });
		},

		save(processing = true) {
			this.startProcessingAjaxCallEvent(processing);
			ApiCaller.request(this.requestURL, this.requestMethod, this.formFields)
				.then(this.requestSuccess)
				.catch(this.requestFailed);
		},

		requestSuccess({ status, data }) {
			if (this.editRecord) {
				this.replaceRecord({
					group: this.group,
					idColumn: this.idColumn,
					record: data.record
				});
			} else if (status == 201) {
				this.goToFirstPageSortByIdDesc(this.group);
			}

			this.cancel();

			if (data.message) {
				this.alert(data.message, 'success', 5000);
			}
		},

		requestFailed(response) {
			if (response && response.data && !response.data.errors) {
				this.cancel();
			}

			ErrorHandler.showError(response, this, this.stopProcessingAjaxCallEvent.bind(this));
		},

		validate() {
			return new Promise((resolve, reject) => {
				if (!this.requiresValidation()) {
					resolve();
					return;
				}

				this.validator.reset().validateAll(resolve, reject);
			});
		},

		requiresValidation() {
			return !Helper.isEmpty(this.validationBag);
		},

		async handleEdit(record) {
			this.editRecord = await this.interceptRecord(record);
			this.formFields = this.populateFields();

			if (this.editRecord) {
				return this.setDisplayForm({ group: this.group, show: true });
			}
			this.reset();
		},

		reset(init = false) {
			this.editRecord = null;
			this.clearErrors();
			this.formFields = this.populateFields();
			this.validator.reset();
			this.setEvent({ group: this.group, type: 'reset' });
			if (!init) this.formInit(this);
		},

		cancel() {
			this.reset();
			this.setDisplayForm({ group: this.group, show: false });
			this.setEvent({ group: this.group, type: 'close' });
		},

		alert(message, style = 'success', timeout = false) {
			this.$page.props.jetstream.flash = {
				banner: message,
				bannerStyle: style,
				location: this.getFormDisplay(this.group) ? this.bannerLocation : 'top',
				timeout
			};
		},

		startProcessingAjaxCallEvent(processing = true) {
			this.processing = processing;
		},

		stopProcessingAjaxCallEvent() {
			this.processing = false;
		}
	},

	computed: {
		...mapGetters('Form', ['getForm', 'getFormDisplay']),

		isEdit() {
			return this.editRecord !== null;
		},

		getFormEvnent() {
			return this.getForm[this.group]?.event;
		},

		requestURL() {
			return this.isEdit && this.editRecord.updateURL
				? this.editRecord.updateURL
				: this.url + (this.editRecord ? '/' + this.editRecord[this.idColumn] : '');
		},

		requestMethod() {
			return this.isEdit ? 'PUT' : 'POST';
		}
	},

	watch: {
		record(record) {
			this.handleEdit(record);
		},

		getFormEvnent({ type, payload }) {
			switch (type) {
				case 'edit':
					this.handleEdit(payload);
					break;
			}
		}
	}
}
</script>
