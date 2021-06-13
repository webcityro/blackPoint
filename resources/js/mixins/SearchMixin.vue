<script>
import { mapActions } from "vuex";

export default {
    methods: {
        ...mapActions({
			setEvent: 'Form/setEvent',
		}),

        initSearch(group, params) {},

        brforeSearch(group, params) {
            return params;
        },

        afterSearch(group, params) {},

        brforeClear(group, params) {
            return params;
        },

        afterClear(group, params) {},

        editRecord(group, record) {
            console.log({edit: record});
            this.setEvent({
                group,
                type: 'edit',
                payload: record
            });
        },

        beforeDeleteRecord(group, record) {
            return Promise.resolve(true);
        },

        afterDeleteRecord(group, record) {},
    }
};
</script>
