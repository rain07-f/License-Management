const AjaxHelper = {

    request: function ({
        url,
        method = "GET",
        data = {},
        beforeSend = null,
        onSuccess = null,
        onError = null
    }) {

        $.ajax({
            url: url,
            type: method,
            data: data,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            },
            beforeSend: function () {
                if (beforeSend) beforeSend();
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
            },
            success: function (response) {
                if (onSuccess) onSuccess(response);
                if (response.message) {
                    // Optional: integrate with a toast notification system if available
                    console.log('Success:', response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    AjaxHelper.showValidation(xhr.responseJSON.errors);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                }
                if (onError) onError(xhr);
            }
        });

    },

    showValidation: function (errors) {
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        $.each(errors, function (key, value) {
            // Handle array-based input names if necessary
            let inputName = key.replace(/\./g, '[').replace(/(\[\d+)/g, '$1]');
            if (key.includes('.')) {
                // Try both original and transformed for flexibility
            }

            let input = $('[name="' + key + '"]');
            if (input.length === 0) {
                input = $('[name="' + inputName + '"]');
            }

            if (input.length > 0) {
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">' + value[0] + '</div>');
            }
        });
    }

};
