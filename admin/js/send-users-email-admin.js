(function ($) {
    'use strict';

    // User email datatable initialization
    $('#sue-user-email-tbl').DataTable({
        "scrollY": "330px",
        "scrollCollapse": true,
        "paging": false
    });
    $('.sue-user-email-datatable-wrapper').show(300);


    // Initialise tinymce
    $("#sue-user-email-btn").mousedown(function () {
        tinyMCE.triggerSave();
    });

    // Check all users
    $("#sueSelectAllUsers").click(function () {
        $('.sueUserCheck').prop('checked', this.checked);
    });

    // User email send ajax process
    $("#sue-users-email-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-user-email-btn").attr('disabled', true);
        showSpinner();
        var postData = $(this).serialize();
        postData += "&action=sue_user_email_ajax&param=send_email_user";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {

            }
        }).fail(function (res) {
            if (res.responseJSON.errors != null) {
                var errors = res.responseJSON.errors;
                for (var field in errors) {
                    var fieldSel = $("." + field);
                    fieldSel.addClass('is-invalid');
                    fieldSel.after('<div class="invalid-feedback error-msg">' + errors[field] + '</div>');
                }
            }
        }).always(function () {
            $("#sue-user-email-btn").attr('disabled', false);
            showSpinner(false);
        });
    });

    function showSpinner(show = true) {
        var spinner = $('.sue-spinner');
        show ? spinner.show() : spinner.hide();
    }

})(jQuery);
