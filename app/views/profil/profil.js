$(document).ready(function () {
    //DIALOG -- Modifciation/Ajout ASSOCIATE - AFFICHAGE
    $(document).on('show.bs.modal', '#modalProfil', function (e) {
        id = $('#dlgInputId').val();
        loadProfil($(this), id, "Associates");
    });
    
/*    //DIALOG --
    $(document).on('click', '#btnAssociatesSave', function (e) {
        saveDlgForm($("#AssociatesForm").serializeObject(), "Associates")
    });*/
});

// Load Profil Dialog box
function loadProfil(dialog, id) {

    if (id) {
        $.ajax({
            type: "GET",
            url: "api/associates/" + id + "&dependency=1",
            headers: { "jwt": localStorage.getItem('jwt') },
            contentType:"application/json"    
        })
        .done(function (data, text, jqxhr) {
            dialog.find('input,select').each(function (index, element) {
                
                field = $(element).attr("name");
                
                if ((field) && data.item.hasOwnProperty(field)) {
                    if ($(element).is('select')) {
                        loadCombo($(element), field, data.item[field]);
                        
                    } else if ($(element).is('input')) {
                        $(element).val(data.item[field])
                    };
                }
            })
            
            $(dialog.find("#headerStatusCaption")).text(data.item._AssociateStatus.name);

            dialog.find(".modal-header").addClass(data.item._AssociateStatus.color)
            
            if (data.item.endDate == '2099-12-31') {
                dialog.find("#dlgInputEndDate").hide();
                dialog.find('label[for="dlgInputEndDate"]').hide();
            }
            if (isAdmin()) {
                dialog.find("#dlgInputApiKey").show();   
                dialog.find('label[for="dlgInputApiKey"]').show();                  
                dialog.find("#dlgInputApiKey").val(localStorage.getItem('jwt'));
            } else {
                dialog.find("#dlgInputApiKey").hide();    
                dialog.find('label[for="dlgInputApiKey"]').hide();            
            }

            dialog.find("#dlgInputMode").val("update");
        })
        .fail(function(jqxhr) {
            // show error to console
            sendAlert(jqxhr.responseJSON.message, 'alert-danger', jqxhr);
            
        })
    }
}
