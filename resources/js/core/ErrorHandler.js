export default class ErrorHandler {
    static showError(
        { response, message, log },
        wrapper,
        beforeCallback = null
    ) {
        if (typeof beforeCallback === "function") {
            beforeCallback();
        }

        if (response && response.data && response.data.errors) {
            wrapper.setBackendErrors({
                group: wrapper.group,
                errors: response.data.errors,
            });
        }

        if (response && response.data && response.data.message) {
            wrapper.alert(response.data.message, "danger");
            return;
        }

        if (message) {
            wrapper.alert(message, "danger");
            return;
        }

        if (log) {
            console.error("Dev error message", log);
            return;
        }

        console.log({ errorHandler: error });
    }
}
