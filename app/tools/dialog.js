function loadEmptyDialog(dialog) {

    dialog.find('input,select').each(function (index, element) {
        field = $(element).attr("name");
        if (field) {
            $(element).val(null);
            
            if ($(element).is('select')) loadCombo($(element), field, "");

            if ("endDate"== field)   $(element).val('2099-12-31');
            if ("startDate"== field) $(element).val(new Date().toISOString().slice(0, 10));
            if ("password"==field)   $(element).parent().removeAttr("hidden");

        }
    })
    dialog.find("#dlgInputMode").val("new");
    dialog.find("#dlgInputId").val("#");
}

function loadDialogData(dialog, id, className) {
    
    $.ajax({
        type: "GET",
        url: "api/" + className + "/" + id + "&dependency=1",
        headers: { "jwt": localStorage.getItem('jwt') },
        contentType:"application/json"    
    })
    .done(function (data, text, jqxhr) {
        dialog.find('input,select').each(function (index, element) {
            
            field = $(element).attr("name");

            if ("password"==field) {
                $(element).parent().attr("hidden", true);

            } else {
                if ((field) && data.item.hasOwnProperty(field)) {
                    if ($(element).is('select')) {
                        loadCombo($(element), field, data.item[field]);
    
                    } else if ($(element).is('input')) {
                        $(element).val(data.item[field])
                    };
                }
            }
        })
        dialog.find("#dlgInputMode").val("update");
    })
    .fail(function(jqxhr) {
        // show error to console
        sendAlert(jqxhr.responseJSON.message, 'alert-danger', jqxhr);

    })
}

function saveDlgForm(dataForm, className) {

    bCreate = (dataForm.mode == "new");
    if (!bCreate && dataForm.hasOwnProperty("password")) delete(dataForm.password);
    if (bCreate) delete(dataForm.id);
    
    $.ajax({
        type: ((bCreate) ? "POST" : "PUT"),
        url: "api/" + className + ((bCreate) ? "" : "/" + dataForm.id),
        headers: { "jwt": localStorage.getItem('jwt') },
        contentType:"application/json",
        data: JSON.stringify(dataForm)
    })
    .done(function (data, text, jqxhr) {
        //Store on the bowser
        $('#modal' + className).modal('hide')
        sendAlert(data.message, "alert-success")

        if (bCreate) {
            $.get('app/views/' + className + '/rowTemplate.html', function (file) {
                $('#listTable tbody').prepend(file);
                updateRowFromArray($('#listTable tbody tr:first'), data['items'])
            });
        } else {
            updateRowFromArray($("#listTable").find('[data-id="' + data['items'].id + '"]'), data['items'])
        }
    })
    .fail(function (jqxhr) {
        if (jqxhr && jqxhr.responseJSON && jqxhr.responseJSON.errors.length>0) {
            for (var id in jqxhr.responseJSON.errors) {
                sendAlert(jqxhr.responseJSON.errors[id], "alert-warning")
            }
        } else {
            // show error to console
            sendAlert(jqxhr.statusText, "alert-warning", jqxhr)
        }
    })
}