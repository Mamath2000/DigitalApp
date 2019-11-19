$(document).ready(function () {
})

function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function jsClassformat(string) {
    var tab = string.split("-");
    tab[0] = jsUcfirst(tab[0]);
    tab[1] = jsUcfirst(tab[1]);

    return tab.join('');
}

function resizeContainer() {
    $('#listTable tbody').height(eval($(window).height() - 400));
}

// change page title
function changePageTitle(page_title) {

    // change page title
    $('#page-title').text(page_title);

    // change title tag
    document.title = page_title;
}

// function Is Conncet
function isConnect() {
    return ((true === localStorage['isConnect']) || ('true' == localStorage['isConnect']));
}

// function Is Admin
function isAdmin() {
    return ((true === localStorage['isAdmin']) || ('true' == localStorage['isAdmin']));
}


function countObj(_Obj) {
    var _count = 0;
    for (var key in _Obj) {
        if (_Obj.hasOwnProperty(key)) _count += 1;
    }
    return _count;
}

function sendAlert(text, color, jqxhr) {

    if (jqxhr && typeof (jqxhr.responseJSON) == 'object'
        && jqxhr.responseJSON.message) text = jqxhr.responseJSON.message;

    if (!color) color = 'alert-success';

    $('#page-alert').prepend(
        `<div class="alert ` + color + `" id="alertMsg"">
          <button type="button" class="close" data-dismiss="alert">x</button>` +
        text +
        `</div>`);
    $("#alertMsg").hide();
    $("#alertMsg").fadeTo(4000, 500).slideUp(500, function () { $("#alertMsg").slideUp(500); });
};

//Supprime un element
function deleteRow(id, tabRow, className) {

    if (!id) {
        sendAlert("Impossible de supprimer l'element, contacter un administrateur", 'alert-danger');
        return false;
    }
    $.ajax({
        type: "DELETE",
        url: "api/" + className + "/" + id,
        headers: { "jwt": localStorage.getItem('jwt') }
    })
        //  $.post(webService, form_data)
        .done(function (data, text, jqxhr) {
            sendAlert("L'associé a été supprimé.", "alert-success")
            tabRow.addClass("zoomOutUp animated")
        })
        .fail(function (jqxhr) {
            if (jqxhr.responseJSON && jqxhr.responseJSON.errors.length > 0) {
                for (var id in jqxhr.responseJSON.errors) {
                    sendAlert(jqxhr.responseJSON.errors[id], "alert-warning")
                }
            } else {
                // show error to console
                sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);
            }
        });
}

// refresh the user tab
function refreshPage(className, filterForm) {

    if (!filterForm) filterForm = {};
    queryFilter = currentFilter || {};

    paramStr = "?dependency=1";
    if (filterForm) {
        queryFilter = {};
        $.each(filterForm, function (index, value) {
            if (!value) {
            } else if ("name" == index) {
                queryFilter["name"] = value;
                paramStr = paramStr + "&name=" + value;
            } else if (index.startsWith('id')) {
                queryFilter[index] = value;
                paramStr = paramStr + "&" + index + "=" + value;
            }
        });
    }

    $.ajax({
        type: "GET",
        url: "api/" + className + paramStr,
        headers: { "jwt": localStorage.getItem('jwt') },
        contentType: "application/json",
    })
        .done(function (data, text, jqxhr) {
            //draw row in tab
            drawTabRow(className, data.data.items);

            //store Filter
            currentFilter = queryFilter;
        })
        .fail(function (jqxhr) {
            // show error to console
            sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

        });
}

// dessine le tableau notamment les ligne
function drawTabRow(className, records) {
    //Login OK
    $('#listTable tbody')[0].innerHTML = "";
    //get row Template HTML
    var rowTemp;
    $.get('app/views/' + className.toLowerCase() + '/rowTemplate.html', function (file) {
        rowTemp = file;
        count = Object.keys(records).length;

        $.each(records, function (index, element) {
            // ADD to tbody
            $('#listTable tbody').append(rowTemp);
            updateRowFromArray($('#listTable tr:last'), element)

            if (!--count) resizeContainer();
        });
    });
}


function getDataWithFormat(field, value) {
    if (!value) return value;

    if (field.endsWith("Datetime")) {
        //val = new Date(value).toLocaleDateString("fr-FR");

    } else if (field.endsWith("Date")) {
        val = new Date(value)
        val = val.toLocaleDateString("fr-FR");

    } else {
        val = value;

    }
    return val;
}


// cette method charge un group d'object HTML notamment une ligne de liste
function updateRowFromArray(htmlRow, data = {}) {

    ///  if (htmlRow.nodeName="tr" && (htmlRow.attr("data-id"))
    htmlRow.find('td,a').each(function (index, element) {
        field = $(element).attr("name");
        if (!field) {
            /// nothing
        } else if (data.hasOwnProperty(field)) {

            $(element).text(getDataWithFormat(field, data[field]));
            if ("id" === field) {
                $(element).closest("tr")
                    .attr("data-id", data[field])
                    .attr("name", "@id")
            }
            if ($(element).attr("title") && data.hasOwnProperty($(element).attr("title"))) {
                $(element).attr("title", data[$(element).attr("title")]);
            }
        } else if (field.indexOf("|") !== -1) {
            var subField = field.split("|");
            if (data.hasOwnProperty(subField[0]) &&
                data[subField[0]].hasOwnProperty(subField[1])) {
                $(element).text(data[subField[0]][subField[1]]);
            }
        }
    });
    // Manage color of Status & Profile
    htmlRow.find('div').each(function (index, element) {
        field = $(element).attr("name");
        if ((field)
            && field.indexOf("|") !== -1
            && field.endsWith("|color")) {

            var subField = field.split("|");
            if (data.hasOwnProperty(subField[0]) &&
                data[subField[0]].hasOwnProperty(subField[1])) {
                $(element).removeClassRegex(/^(bg-.*)/)
                $(element).addClass(data[subField[0]][subField[1]]);
            }
        }
    });

}

// Chargement d'un combo. la source est une table de référence.
function loadCombo(htmlCombo, field, selectValue = "", mandatory = false, urlParam = "", funcDone = function () { }) {

    htmlCombo.empty();

    if ("id" == field.substr(0, 2)) {
        comboClass = jsUcfirst(field.substr(2, field.length));
    } else {
        return false;
    }

    //Web Service URL
    $.ajax({
        type: "GET",
        url: "api/" + comboClass + urlParam,
        headers: { "jwt": localStorage.getItem('jwt') },
        contentType: "application/json",
        htmlCombo: htmlCombo,
        selectValue: selectValue
    })
        .done(function (data, text, jqxhr) {
            if (!mandatory) this.htmlCombo.append("<option " + (((!this.selectValue)) ? "selected" : "") + " value=''></option>")
            if (data.data.items) {
                for (var id in data.data.items) {
                    element = data.data.items[id];

                    $name = ((element.hasOwnProperty("firstname")) ? element.firstname + " " : "") + element.name;

                    this.htmlCombo.append("<option " +
                        ((element.id == this.selectValue) ? "selected" : "") +
                        " value='" + element.id + "'>" + $name + "</option>")
                }
            }
            funcDone();
        })
        .fail(function (jqxhr) {
            // show error to console
            sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

        });
}
