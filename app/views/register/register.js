$(document).ready(function () {

    $(document).on('click', '#btnUserReg', function (e) {

        var form_data = JSON.stringify($('#UserRegForm').serializeObject());
        var webService = "api/user/create.php";

        $.post(webService, form_data)
            .done(function (data, text, jqxhr) {
                //Login OK
                if (data.status === 'OK' || data.status === 'NO_CHANGE') {
                    sendAlert(data.message, "alert-success")
                    showPage('login', 'Merci de vous identifier');

                } else {
                    sendAlert(data.message, "alert-warning")

                }
            })
            .fail(function (jqxhr) {
                // show error to console
                sendAlert(jqxhr.responseJSON.message, 'alert-danger');
                
            });
    });


});

// Call by app.js when page is charge.
function onloadRegisterScreen() {

};
