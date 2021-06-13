<template>
    <div v-if="show && message" :class="['alert', 'alert-'+style]" role="alert">{{ message }}</div>
</template>

<script>
import BaseAlert from "../../mixins/BaseAlert";

export default {
    mixins: [BaseAlert],
};
</script>

<style scoped>
    .alert {
        margin-bottom: 0;
    }
</style>
