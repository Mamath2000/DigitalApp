$(document).ready(function () {

    $(document).on('click', '#btnLogIn', function (e) {
        $.ajax({
            type: "PUT",
            dataType: "json",
            data: JSON.stringify($('#loginSigninForm').serializeObject()),
            url: "api/login"
        })
            .done(function (data, text, jqxhr) {
                localStorage['jwt'] = data.jwt;

                $.ajax({
                    type: "GET",
                    url: "api/users/check",
                    headers: { "jwt": localStorage.getItem('jwt') }
                })
                    .done(function (data, text, jqxhr) {
                        //Login OK
                        $.each(data.data, function (key, value) {
                            localStorage['User' + jsUcfirst(key)] = value;
                        });
                        localStorage['isAdmin'] = ('10' == data.data.idUserProfile);
                        localStorage['isConnect'] = ('1' == data.data.idUserStatus);
                        sendAlert("Vous êtes connecté.")
                        showPage('home', 'H o m e  . . . ');

                    })
                    .fail(function (jqxhr) {
                        // show error to console
                        message = "Erreur dans la validation du token, contacter votre administateur";
                        message += jqxhr.responseJSON.message + " [" + jqxhr.statusText + "]";
                        sendAlert(message, "alert-danger")

                    })
            })

            .fail(function (jqxhr) {
                // show error to screen
                sendAlert(jqxhr.statusText, "alert-warning", jqxhr)

            })
    });


});

// Call by app.js when page is charge.
function onloadLoginScreen() {

};
