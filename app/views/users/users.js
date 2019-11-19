$(document).ready(function () {
    //Gestion des filtres
    $(document).on('click', '#btnUserStatusSelect button', function (e) {
        $("#txtTextHdn").val($(e.target).attr('value'));
        $(".btn-group button").removeClass("active");
        $(e.target).addClass("active");
        e.preventDefault();

        //refreshPage
        refreshPage("Users", $("#UserSearchForm").serializeObject());
    });

    $(document).on('click', '#btnUserNameFilter', function (e) {
        e.preventDefault();

        //refreshPage
        refreshPage("Users", $("#UserSearchForm").serializeObject());
    });

    $(document).on('click', '#btnAddUser', function (e) {
        e.preventDefault();

        $('#dlgInputId').val("");
        $('#modalUsers').modal('show');
    });

    $(document).on('click', '.rowUserAction', function (e) {
        e.preventDefault();

        var action = ($(e.target).is("i") ? $(e.target).parent() : $(e.target)).attr("data-action");
        tabRow = $(e.target).closest('tr')
        id = tabRow.find('td[name="id"]').text();

        switch (action) {
            case "edit":
                $('#dlgInputId').val(id);
                $('#modalUsers').modal('show');

                break;
            case "password":
                modifyPassword(id);
                break;

            case "delete":
                bootbox.confirm({
                    message: "Vous est sur le point de supprimer cet utilisateur. Confirmer cette commande ?",
                    locale: 'fr',
                    centerVertical: true,
                    backdrop: true,
                    className: 'rubberBand animated',
                    buttons: {
                        cancel: { className: 'btn-secondary' },
                        confirm: { className: 'btn-danger' }
                    },
                    callback: function (result) {
                        if (result) deleteRow(id, tabRow, "Users");
                    }
                });

                break;
            default:

        }
    });
    //resizeContainer();
    $(window).resize(function () {
        resizeContainer();
    });

    //DIALOG -- Modifciation/Ajout ASSOCIATE - AFFICHAGE
    $(document).on('show.bs.modal', '#modalUsers', function (e) {
        id = $('#dlgInputId').val();
        if (id) {
            loadDialogData($(this), id, "Users");
        } else {
            loadEmptyDialog($(this));
        }
    });

    //DIALOG --
    $(document).on('click', '#btnUsersSave', function (e) {
        saveDlgForm($("#UsersForm").serializeObject(), "Users");
    });

});


/**
 * Call by app.js when page is charge.
 * 
 */
function onloadUsersScreen() {

    currentFilter = {};

    //refreshPage
    refreshPage("Users");
};

/**
 * Reset or update password 
 * 
 * @param {any} userId 
 */
function modifyPassword(userId) {
    bootbox.prompt({
        title: "Réinitialisation de mot de passe.",
        locale: 'fr', inputType: 'password', centerVertical: true,
        buttons: {
            cancel: { className: 'btn-secondary' },
            confirm: { className: 'btn-success' }
        },
        callback: function (result1) {
            if (result1) {
                bootbox.prompt({
                    title: "Résaisir de nouveau le mot de passe.",
                    locale: 'fr', inputType: 'password', centerVertical: true,
                    buttons: {
                        cancel: { className: 'btn-secondary' },
                        confirm: { className: 'btn-success' }
                    },
                    callback: function (result2) {
                        if ((result2) && result1 == result2) {

                            var jsonQuery = {};
                            jsonQuery['password'] = result1;

                            $.ajax({
                                type: "PUT",
                                url: "api/users/" + userId + "/password",
                                dataType: "json",
                                data: JSON.stringify(jsonQuery),
                                headers: { "jwt": localStorage.getItem('jwt') }
                            })
                                .done(function (data, text, jqxhr) {
                                    sendAlert("Le mot de passe a été modifié avec succès", "alert-success")

                                })
                                .fail(function (jqxhr) {
                                    // show error to console
                                    sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

                                });
                        } else {
                            sendAlert("Les mot de passe ne sont pas identique, merci de recommancer.", "alert-warning")

                        };
                    }
                });
            };
        }
    });

}
