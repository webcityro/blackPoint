<template>
	<form-group :id="id" :label="label" :group="group" :rules="rules">
		<CheckboxGroup
			:id="id"
			:group="group"
			:rules="rules"
			:options="options"
			:select-all-label="selectAllLabel"
			:show-select-all="showSelectAll"
			v-model="selected"
			@change="emit"
		/>
	</form-group>
</template>

<script>
import FormGroupBase from "../../mixins/FormGroupBase";
import CheckboxBase from "../../mixins/CheckboxBase";

export default {
	mixins: [FormGroupBase, CheckboxBase]
};
</script>

<style>
</style>
