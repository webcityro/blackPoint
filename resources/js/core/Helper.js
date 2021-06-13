export default class Helper {
    static isEmpty(value) {
        if (Helper.isObject(value)) {
            return Object.keys(value).length === 0;
        }

        return (
            value === undefined ||
            value === null ||
            value.length === 0 ||
            (typeof value === "string" && value.trim() === "")
        );
    }

    static isObject(value) {
        return value instanceof Object && !Array.isArray(value);
    }

    static removeObjectProperties(wrapper, indexes, obj, parentKey) {
        Object.keys(obj).forEach(key => {
            const newParentKey = (parentKey ? parentKey + "." : "") + key;

            if (!Helper.isObject(obj[key])) {
                if (
                    !indexes.includes(newParentKey) ||
                    !obj.hasOwnProperty(key)
                ) {
                    return;
                }

                wrapper.$delete(obj, key);
            } else {
                Helper.removeObjectProperties(
                    wrapper,
                    indexes,
                    obj[key],
                    newParentKey
                );
            }
        });
    }

    static arrayToQueryString(collection, field) {
        return collection
            .map(value => {
                return field + "[]=" + encodeURIComponent(value);
            })
            .join("&");
    }
}
