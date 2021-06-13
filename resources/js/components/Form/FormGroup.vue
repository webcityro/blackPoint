<template>
	<div :class="computedWrapperCssClasses">
		<label :for="id" :class="computedLabelCssClasses">{{ label }}</label>
		<slot
			:inputClass="computedInputCssClasses"
			:selfValidation="selfValidation"
		>
			<input v-bind="computedAttrs" />
		</slot>
		<div v-if="hasFeedback" :class="computedFeedbackCssClasses">
			{{ getFeedback }}
		</div>
	</div>
</template>

<script>
import FormGroupBase from "../../mixins/FormGroupBase";

export default {
	mixins: [FormGroupBase]
}
</script>
