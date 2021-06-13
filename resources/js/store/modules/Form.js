import Validator from "@/validator/Validator";

const recursiveSetter = (path, msg, obj) => {
    const pathArr = path.split(".");
    const key = pathArr.shift();
    obj[key] =
        pathArr.length === 0
            ? msg
            : recursiveSetter(pathArr.join("."), msg, obj[key]);
    return obj;
};

export default {
    namespaced: true,

    state: {
        form: {},
    },

    getters: {
        getRules(state) {
            return (group) => state.form[group].validationBag;
        },

        getAllErrors(state) {
            return (group) => state.form[group].errors;
        },

        hasError(state) {
            return (group, field) =>
                state.form[group].errors.hasOwnProperty(field);
        },

        getError(state, getters) {
            return (group, field) =>
                getters.hasError(group, field)
                    ? state.form[group].errors[field][0]
                    : null;
        },

        getValidator(state) {
            return (group) => state.form[group].validator;
        },

        getForm(state) {
            return state.form;
        },

        getFormDisplay(state) {
            return (group) => state.form[group].showForm;
        },
    },

    actions: {
        init({ commit }, wrapper) {
            return new Promise((resolve, reject) => {
                commit("SET_INITIAL_FORM", wrapper);
                resolve();
            });
        },

        setRules({ commit }, payload) {
            commit("SET_RULES", payload);
        },

        removeRules({ commit }, payload) {
            commit("REMOVE_RULES", payload);
        },

        setEvent({ commit }, payload) {
            commit("SET_EVENT", payload);
        },

        setValidField({ commit }, payload) {
            commit("SET_VALID_FIELD", payload);
        },

        removeValidField({ commit }, payload) {
            commit("REMOVE_VALID_FIELD", payload);
        },

        clearValidFields({ commit }, payload) {
            commit("CLEAR_VALID_FIELDS", payload);
        },

        setBackendErrors({ commit }, { group, errors }) {
            let err = {};

            for (const key in errors) {
                err = {
                    ...err,
                    ...recursiveSetter(key, errors[key], {}),
                };
                commit("REMOVE_VALID_FIELD", { group, field: key });
                commit("CLEAR_VALID_FIELDS", group);
            }

            commit("SET_ERRORS", { group, errors: err });
        },

        setErrors({ commit }, { group, errors }) {
            commit("SET_ERRORS", { group, errors });
        },

        setDisplayForm({ commit }, { group, show }) {
            commit("SET_SHOW_FORM", { group, show });
        },
    },

    mutations: {
        SET_INITIAL_FORM(state, wrapper) {
            state.form = {
                ...state.form,
                [wrapper.group]: {
                    event: {
                        type: null,
                        payload: null,
                    },
                    errors: {},
                    validationBag: {},
                    validFields: [],
                    validator: new Validator(wrapper),
                    showForm: false,
                },
            };
        },

        SET_RULES(state, { group, rules }) {
            state.form[group].validationBag = {
                ...(state.form[group].validationBag || {}),
                ...rules,
            };
        },

        REMOVE_RULES(state, { group, field }) {
            delete state.form[group].validationBag[field];
        },

        SET_VALID_FIELD(state, { group, field }) {
            if (!state.form[group].validFields.includes(field))
                state.form[group].validFields.push(field);
        },

        REMOVE_VALID_FIELD(state, { group, field }) {
            state.form[group].validFields = state.form[
                group
            ].validFields.filter((f) => f != field);
        },

        CLEAR_VALID_FIELDS(state, group) {
            state.form[group].validFields = [];
        },

        SET_EVENT(state, { group, type, payload }) {
            state.form[group] = {
                ...state.form[group],
                event: { type, payload },
            };
        },

        SET_ERRORS(state, { group, errors }) {
            state.form[group] = {
                ...state.form[group],
                errors,
            };
        },

        SET_SHOW_FORM(state, { group, show }) {
            state.form[group] = {
                ...state.form[group],
                showForm: show,
            };
        },
    },
};
