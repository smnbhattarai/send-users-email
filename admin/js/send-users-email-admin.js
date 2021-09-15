(function ($) {
    'use strict';

    // User email datatable initialization
    $('#sue-user-email-tbl').DataTable({
        "scrollY": "330px",
        "scrollCollapse": true,
        "paging": false,
        "order": [[1, "asc"]]
    });

    setTimeout(function () {
        $('.sue-user-email-datatable-wrapper').css("visibility", "visible");
    }, 800);


    // Initialise tinymce
    $("#sue-user-email-btn").mousedown(function () {
        tinyMCE.triggerSave();
    });

    // Check all users
    $("#sueSelectAllUsers").click(function () {
        $('.sueUserCheck').prop('checked', this.checked);
    });

    // Track Email send progress
    var email_users_email_progress;

    // User email send ajax process
    $("#sue-users-email-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-user-email-btn").attr('disabled', true);
        showSpinner();
        $('.progress').show();
        var postData = $(this).serialize();
        postData += "&action=sue_user_email_ajax&param=send_email_user";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                $('.sue-messages').html('<h3 class="text-success">Emails send Successfully.</h3>');
                $("#sue-users-email-form").remove();
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
            clearInterval(email_users_email_progress);
            $("#sue-user-email-btn").attr('disabled', false);
            showSpinner(false);
            $('.progress').hide();
        });

        // Get data to monitor email send progress
        email_users_email_progress = setInterval(function () {
            $.get(ajaxurl, {action: "sue_email_users_progress", "param": "send_email_user_progress"}, function (res) {
                var progressSel = $('.progress-bar');
                progressSel.attr("aria-valuenow", res.progress);
                progressSel.text(res.progress + "%");
                progressSel.css('width', res.progress + "%");
            });
        }, 5000);

    });



    var email_roles_email_progress;

    // Initialise tinymce
    $("#sue-roles-email-btn").mousedown(function () {
        tinyMCE.triggerSave();
    });

    // Role email send ajax process
    $("#sue-roles-email-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-roles-email-btn").attr('disabled', true);
        showSpinner();
        $('.progress').show();
        var postData = $(this).serialize();
        postData += "&action=sue_role_email_ajax&param=send_email_role";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                $('.sue-messages').html('<h3 class="text-success">Emails send Successfully.</h3>');
                $("#sue-roles-email-form").remove();
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
            clearInterval(email_roles_email_progress);
            $("#sue-roles-email-btn").attr('disabled', false);
            showSpinner(false);
            $('.progress').hide();
        });

        // Get data to monitor email send progress
        email_roles_email_progress = setInterval(function () {
            $.get(ajaxurl, {action: "sue_email_roles_progress", "param": "send_email_role_progress"}, function (res) {
                var progressSel = $('.progress-bar');
                progressSel.attr("aria-valuenow", res.progress);
                progressSel.text(res.progress + "%");
                progressSel.css('width', res.progress + "%");
            });
        }, 5000);

    });

    function showSpinner(show = true) {
        var spinner = $('.sue-spinner');
        show ? spinner.show() : spinner.hide();
    }

})(jQuery);
