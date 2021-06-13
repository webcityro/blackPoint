import Rule from "./Rule";

export default class Validator {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.reset();
    }

    reset(field = false) {
        if (field) {
            delete this.errors[field];
            return this;
        }
        this.errors = {};
        return this;
    }

    validateAll(resolve, reject) {
        Promise.all(this.promises())
            .then(() => {
                resolve();
            })
            .catch((error) => {
                this.wrapper.setErrors({
                    group: this.wrapper.group,
                    errors: this.errors,
                });
                reject(error);
            });
    }

    promises() {
        const promises = [];

        for (let field in this.wrapper.validationBag) {
            promises.push(this.validate(field));
        }

        return promises;
    }

    validate(field) {
        return new Promise((resolve, reject) => {
            const rules = this.wrapper.validationBag[field];
            const value = this.getValue(field);

            for (const key in rules) {
                const [rule, params] = key.split(":");

                if (params) {
                    params = params.includes(",") ? params.split(",") : params;
                }

                try {
                    if (!Rule[rule](value, params)) {
                        this.errors[field] = [
                            ...(this.errors[field] || []),
                            rules[key],
                        ];
                        this.wrapper.removeValidField({
                            group: this.wrapper.group,
                            field,
                        });
                        reject({
                            field,
                            errors: this.errors[field],
                            message: "The data was invalid.",
                        });
                    }
                } catch (error) {
                    reject({
                        log:
                            "Invalid form validation rule: " +
                            rule +
                            " at field " +
                            field,
                    });
                }

                if (Object.keys(rules)[Object.keys(rules).length - 1] == key) {
                    this.wrapper.setValidField({
                        group: this.wrapper.group,
                        field,
                    });

                    resolve();
                }
            }
        });
    }

    getValue(field) {
        return field
            .split(".")
            .reduce((form, f) => form[f], this.wrapper.formFields);
    }
}
