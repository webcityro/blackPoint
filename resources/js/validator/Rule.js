import Helper from "../core/Helper";

export default class Rule {
    static required(value, param) {
        return !Helper.isEmpty(value);
    }

    static validateIfNotEmpty(value, callback) {
        if (Helper.isEmpty(value)) {
            return true;
        }
        return callback();
    }

    static numeric(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            return !isNaN(value);
        });
    }

    static email(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            const regex = /^[a-zA-Z0-9._\-]+@[a-zA-Z0-9]+([.\-]?[a-zA-Z0-9]+)?([\.]{1}[a-zA-Z]{2,4}){1,4}$/;
            return regex.test(value);
        });
    }

    static password(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]/;
            return regex.test(value);
        });
    }

    static accepted(value, param) {
        return ["yes", "on", "1", 1, true].includes(value);
    }

    static alpha(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            const regex = /[a-zA-Z]+/;
            return regex.test(value);
        });
    }

    static alnum(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            const regex = /[a-zA-Z0-9]+/;
            return regex.test(value);
        });
    }

    static alphaDash(value, param) {
        return Rule.validateIfNotEmpty(value, () => {
            const regex = /[a-zA-Z0-9\-\_]+/;
            return regex.test(value);
        });
    }

    static length(value, param) {
        if (typeof value === "string") {
            return Rule.validateIfNotEmpty(
                value,
                () => value.length == param[0]
            );
        }

        return (
            (Array.isArray(value) ? value : Object.keys(value)).length ==
            param[0]
        );
    }

    static lengthRange(value, param) {
        if (typeof value === "string") {
            return Rule.validateIfNotEmpty(
                value,
                () =>
                    value.length >= (param[0] || param.min) &&
                    value.length <= (param[1] || param.max)
            );
        }

        return (
            Object.keys(value).length >= (param[0] || param.min) &&
            Object.keys(value).length <= (param[1] || param.max)
        );
    }

    static max(value, param) {
        if (typeof value === "string") {
            return Rule.validateIfNotEmpty(value, () => value.length <= param);
        }

        return Object.keys(value).length <= param;
    }

    static min(value, param) {
        if (typeof value === "string") {
            return Rule.validateIfNotEmpty(value, () => value.length >= param);
        }

        return Object.keys(value).length >= param;
    }

    static in(value, param) {
        return Rule.validateIfNotEmpty(value, () =>
            param
                .split(",")
                .map(item => item.trim())
                .includes(value.toString())
        );
    }
}
