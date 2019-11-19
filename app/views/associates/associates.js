$(document).ready(function () {
    //Gestion des filtres
    $(document).on('click', '#btnAssociateStatusSelect button', function (e) {
        $("#txtTextHdn").val($(e.target).attr('value'));
        $(".btn-group button").removeClass("active");
        $(e.target).addClass("active");
        e.preventDefault();

        //refreshPage
        refreshPage("Associates", $("#AssociateSearchForm").serializeObject());
    });

    $(document).on('click', '#btnAssociateNameFilter', function (e) {
        e.preventDefault();

        //refreshPage
        refreshPage("Associates", $("#AssociateSearchForm").serializeObject());
    });

    $(document).on('click', '#btnAddAssociate', function (e) {
        e.preventDefault();

        // Add associate
        $('#dlgInputId').val("");
        $('#modalAssociates').modal('show');
    });

    $(document).on('click', '.rowAssociateAction', function (e) {
        e.preventDefault();

        var action = ($(e.target).is("i") ? $(e.target).parent() : $(e.target)).attr("data-action");
        tabRow = $(e.target).closest('tr')
        id = tabRow.find('td[name="id"]').text();

        switch (action) {
            case "edit":
                // Modify Associate
                $('#dlgInputId').val(id);
                $('#modalAssociates').modal('show');

                break;

            case "delete":
                bootbox.confirm({
                    message: "Vous est sur le point de supprimer cet enregistrement. Confirmer cette commande ?",
                    locale: 'fr',
                    centerVertical: true,
                    backdrop: true,
                    className: 'rubberBand animated',
                    buttons: {
                        cancel: { className: 'btn-secondary' },
                        confirm: { className: 'btn-danger' }
                    },
                    callback: function (result) {
                        if (result) deleteRow(id, tabRow, "Associates");
                    }
                });

                break;
            default:

        }
    });

    $(window).resize(function () {
        resizeContainer();
    });

    //DIALOG -- Modifciation/Ajout ASSOCIATE - AFFICHAGE
    $(document).on('show.bs.modal', '#modalAssociates', function (e) {
        id = $('#dlgInputId').val();
        if (id) {
            loadDialogData($(this), id, "Associates");
        } else {
            loadEmptyDialog($(this));
        }
    });
    
    //DIALOG --
    $(document).on('click', '#btnAssociatesSave', function (e) {
        saveDlgForm($("#AssociatesForm").serializeObject(), "Associates")
    });
});

// Call by app.js when page is charge.
function onloadAssociatesScreen() {
    //reset ActiveFilter
    currentFilter = {};

    //refreshPage
    refreshPage("Associates");
};


