$(document).ready(function () {

    $(document).on('click', '#btnRefreshDataGrid', function (e) {
        refreshReport();
    });
    
    $(document).on('click', '#btnSaveDataGrid', function () {
        saveDataGrid();
    });

    $(document).on('click', '#btnCalculDataGrid', function () {
        calcReport();
    });

    $(document).on('click', '#btnLockDataGrid', function () {
        lockReport(true, refreshReport);
    });

    $(document).on('click', '#btnUnLockDataGrid', function () {
        lockReport(false, refreshReport);
    });
    
    $(document).on('click', 'i.isCalc', function (e) {
        var htmlRow = e.target.closest('tr');
        calculRow(htmlRow);
    });
    
    $(document).on('change', '#forComboAssociates', function (e) {
        idReports = $("#idReports").val();
        idAssociates = $(e.currentTarget).val();

        urlParam = "?idReports=" + idReports;

        // Load Year Combo
        loadCombo($("#forComboYear"), "idAssociates/" + idAssociates + "/timelines", "", true, urlParam, onComboLoading);
    });

    $(document).on('change', '#forComboYear', function (e) {
        refreshReport();
    });

    $(document).on('change', '.form-control', function (e) {

        _input = $(e.target);

        if (isNaN(_input.val())) {
            _input.removeClass("dataSaved");
            _input.removeClass("dataChange");
            _input.addClass("dataError");

        } else {
            _bChange = _input.attr("data-value") == "" && _input.val() != "";
            _bChange = _bChange || ((_input.attr("data-value") * 1) != (_input.val() * 1));
            if (_bChange) {
                _input.removeClass("dataSaved");
                _input.addClass("dataChange");

            } else if (_input.val()) {
                _input.removeClass("dataChange");
                _input.addClass("dataSaved");

            } else {
                _input.removeClass("dataChange");
                _input.removeClass("dataSaved");
            }
        }

        var cnt = $('#listTable').find('.dataChange').length || 0;
        if (cnt !== 0) {
            $('#btnSaveDataGrid').removeAttr("disabled");
        } else {
            $('#btnSaveDataGrid').attr("disabled", "");
        }
    });

//    resizeContainer();
    $(window).resize(function() {
        resizeContainer();
    });

});

// Call by app.js when page is charge.
function onloadReportsScreen(ReportId) {

    $("#idReports").val(ReportId);

    idAssociates = localStorage.getItem('UserIdAssociates');

    urlParam = "?idReports=" + ReportId;

    // Load Year Combo
    if (!isNaN(idAssociates)) {
        loadCombo($("#forComboYear"), "idAssociates/" + idAssociates + "/timelines", "", true, urlParam);    
    }

    // Load Assciates Combo
    loadCombo($("#forComboAssociates"), "idAssociates",((isAdmin) ? "" : (isNaN(idAssociates) ? "" : idAssociates)), false, []);
    if (!isAdmin()) {
        $("#forComboAssociates").val(localStorage.getItem('UserIdAssociates'));
        $("#forComboAssociates").attr("disabled",true);
    } else {
        $("#forComboAssociates").removeAttr("disabled");
    }

    refreshReport();    
    $('#dataGridChanges').val(0);

};

function onComboLoading() {
    year = new Date().getFullYear();
    element = $("#forComboYear").find("option[value=" + year + "]");
    if (element.is("option")) {
        element.attr("selected", true);
    } else {
        $("#forComboYear").prepend("<option selected value='" + year + "'>" + year + "</option>");
    }

    refreshReport();
}

function resizeContainer() {
    //ratio =eval($(window).height()/$(window).width());
    $("#myChart").height($(window).height()-200);
    $("#myChart").width($(window).width()-30);
    //$('#listTable tbody').height(eval($(window).height()-400));
}

//this function lock or unlock the selected report
function lockReport(lock, callbackFunction) {
    
    crit = $('#dataGridSearchForm').serializeObject();
    if (!crit.idAssociates ||!crit.year || !crit.idReports) {
        // show error to console
        sendAlert("Merci de raffraichir le rapport avant de " + (lock ? "verrouiller." : "déverrouiller."), 'alert-warning');
        return false;
    };

    //add Param to query
    criteria = {};
    criteria['idReports'] = $("#idReports").val();
    criteria['idAssociates'] = $('#listTable').attr('data-idAssociates');
    criteria['year'] = $('#listTable').attr('data-year');
    criteria['isLock'] = (lock ? 1 : 0);

    //Web Service api/reports
    $.ajax({
        type:    (lock ? "POST" : "PUT"),
        url:     "api/locks",
        headers: { "jwt": localStorage.getItem('jwt') }, 
        dataType: "json",
        data: JSON.stringify(criteria)
    })
    .done(function (data, text, jqxhr) {
        sendAlert("Le rapport a été " + (lock ? "verrouillé." : "déverrouillé.") , "alert-success")
        
        callbackFunction();
    })
    .fail(function (jqxhr) {
        // show error to console
        sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

    });
}

//recalcul a row, for calculate specific row
function calculRow(htmlRow) {

    //Get id of line
    var idLinesDef = $(htmlRow).attr('data-id');
    var rowCode = $(htmlRow).attr('data-code');

    //add Param to query
    idAssociates = $('#listTable').attr('data-idAssociates');

    criteria = {};
    criteria['idLinesDef'] = idLinesDef;
    criteria['year'] = $('#listTable').attr('data-year');

    //Web Service api/reports
    $.ajax({
        type:    "PUT",
        url:     "api/associates/" + idAssociates + "/rowcalc",
        headers: { "jwt": localStorage.getItem('jwt') }, 
        dataType: "json",
        data: JSON.stringify(criteria)
    })
    .done(function (data, text, jqxhr) {
        sendAlert("La ligne a été recalculé.", "alert-success")
        updateDataGridRowFromArray($(htmlRow), null, data.data[rowCode]);

    })
    .fail(function (jqxhr) {
        // show error to console
        sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

    });
}

//Recalc all cells of the datagrid
function calcReport() {
    
    //add Param to query
    idAssociates = $('#listTable').attr('data-idAssociates');

    criteria = {};
    criteria['idReports'] = $("#idReports").val();
    criteria['year'] = $('#listTable').attr('data-year');

    //Web Service api/reports
    $.ajax({
        type:    "PUT",
        url:     "api/associates/" + idAssociates + "/reportcalc",
        headers: { "jwt": localStorage.getItem('jwt') }, 
        dataType: "json",
        data: JSON.stringify(criteria)
    })
    .done(function (data, text, jqxhr) {
        sendAlert("Le rapport a été recalculé.", "alert-success")
        
        vRows = $('#listTable').find("tr[iscalculate='true']");
        for (var i = 0; i < vRows.length; i++) {
            var htmlRow = $(vRows[i]);
            var code = $(htmlRow).attr('data-code');
            if (data.data[code]) {
                updateDataGridRowFromArray($(htmlRow), null, data.data[code]);
            }
        }

    })
    .fail(function (jqxhr) {
        // show error to console
        sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

    });

}

//Save all modification of the datagrid
function saveDataGrid() {

    //add Param to jwt to query
    criteria = {};
    criteria['cells'] = {};
    
    vCells = $('#listTable').find('.dataChange');
    year = $('#listTable').attr('data-year');
    idAssociates = $('#listTable').attr('data-idAssociates');

    for (var i = 0; i < vCells.length; i++) {
        var htmlCell = $(vCells[i]);
        var htmlRow = htmlCell.closest('tr');

        var id = htmlCell.attr('data-id');
        _rec = {};

        if ((id) && !isNaN(id)) {
            _rec['id'] = id;
        } else {
            id = "x" + i;
            htmlCell.attr('data-id', id);
        };

        //clef de la cellule = idLinesDef + idAssociates + refLabel
        _rec['idLinesDef'] = htmlRow.attr('data-id');
        _rec['idAssociates'] = idAssociates;
        _rec['year'] = year;

        if (isNaN(htmlCell.attr('name'))) {
            _rec['refLabel']        = htmlCell.attr('name');
            _rec['dateValueDate']   = ((year*1)+1) + "-06-30";
            _rec['dateRealDate']    = ((year*1)+1) + "-06-30";
    
        } else {
            _rec['refLabel'] = (((htmlCell.attr('name') * 1) > 6) ? year : (year * 1 + 1)) + htmlCell.attr('name');
            _rec['dateValueDate'] = (((htmlCell.attr('name') * 1) > 6) ? year : (year * 1 + 1)) + "-" + htmlCell.attr('name') + "-01";
            _rec['dateRealDate'] = (((htmlCell.attr('name') * 1) > 6) ? year : (year * 1 + 1)) + "-" + htmlCell.attr('name') + "-01";
    
        }       
        _rec['source'] = "manual"
        _rec['value'] = htmlCell.val();
        //      _rec[idGrpCodeDefinition]   = htmlRow.attr('data-idGrp');
        criteria['cells'][id] = _rec;
    }

    //Web Service api/reports
    $.ajax({
        type:    "PUT",
        url:     "api/associates/" + idAssociates + "/cells",
        headers: { "jwt": localStorage.getItem('jwt') }, 
        dataType: "json",
        data: JSON.stringify(criteria)
    })
    .done(function (data, text, jqxhr) {

        $.each(data.data, function (index, element) {
            vCell = $('#listTable').find("input[data-id='" + index + "']");
            vCell.removeClass("dataChange");

            if ('OK' === element._info.status || 'NO_CHANGE' === element._info.status) {
                vCell.addClass("dataSaved");
                vCell.attr("data-id", element.id);
                vCell.val(element.value);
            } else {
                vCell.addClass("dataError");

            }
        });
    })
    .fail(function (jqxhr) {

        // show error to console
        sendAlert(jqxhr.responseJSON.message, 'alert-danger', jqxhr);

    });
};

// refresh the user tab
function refreshReport() {

    crit = $('#dataGridSearchForm').serializeObject();
    if (!crit.idAssociates ||!crit.year || !crit.idReports) return null;

    urlParam = "?idReports=" + crit.idReports + "&year=" + crit.year ;
    //http://localhost/ajax/api/associates/19/cells?year=2018&idReports=2

    //Web Service api/reports
    $.ajax({
        type:    "GET",
        url:     "api/associates/" + crit.idAssociates + "/cells" + urlParam,
        headers: { "jwt": localStorage.getItem('jwt') }, 
    })
    .done(function (data, text, jqxhr) {

        $('#listTable').attr("hidden", "");
    
        //draw row in tab
        drawDataGridRow(data.reports);

    })
    .fail(function (jqxhr) {
        // show error to console
        sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);

    });
}

// dessine le tableau notamment les ligne
function drawDataGridRow(data) {

    $('#listTable').attr("data-idAssociates", data._idAssociates);
    $('#listTable').attr("data-year", data._year);

    window.isReportLocked = (data.isLocked);
    window.isReportReadOnly = !isAdmin();

    if (window.isReportReadOnly) {
        $("#btnCalculDataGrid, #btnSaveDataGrid, #btnLockDataGrid", "#btnUnLockDataGrid").attr("hidden", true);
    } else {
        if (window.isReportLocked) {
            $("#btnLockDataGrid, #btnCalculDataGrid, #btnSaveDataGrid").attr("hidden", true);
            $("#btnUnLockDataGrid").removeAttr("hidden");
            $("#btnUnLockDataGrid").attr("enabled", true)

        } else {
            $("#btnUnLockDataGrid").attr("hidden", true);
            $("#btnLockDataGrid, #btnCalculDataGrid, #btnSaveDataGrid").removeAttr("hidden");
        }
    }

    $('#listTable tbody')[0].innerHTML="";

    $.get('app/views/reports/rowTemplate.html', function (rowTemplate) {

        lastHtmlElement = $('#listTable tfoot')

        updateDataGridGroupFromArray(lastHtmlElement, rowTemplate, data._linesArray);

    });
}

function updateDataGridGroupFromArray(lastHtmlElement, htmlRowTemplate, vLineArray) {

    lastHtmlElement = $('#listTable tbody');

    bAddline = false;
    for (var idLine in vLineArray) {
        vLine = vLineArray[idLine];
        if (vLine.isHidden == 0) {
            //iRef = vLine.id;
            vDataRow = (vLine.cells) ? vLine.cells : {};
            
            // ADD to tbody
            lastHtmlElement.append(htmlRowTemplate);
            updateDataGridRowFromArray(lastHtmlElement.find('tr:last'), vLine, vDataRow);

            bAddline = true;
        }
    }
    if (bAddline) {
        $('#listTable').removeAttr("hidden");
        resizeContainer();
    } 
}

function setRowParam(htmlElement, fieldName, value) {
    htmlElement.attr(fieldName, value); 
    return value;
}

function getRowParam(htmlElement, fieldName) {
    var val = htmlElement.attr(fieldName); 
    if (!val) return false;
    return val;
}

//affiche et remplis la ligne de la grille
function updateDataGridRowFromArray(htmlRow, lineRef = null, dataValue) {

    year = htmlRow.closest('table').attr('data-year');

    if (lineRef) {
        // Param de la ligne
        var isReadonly = setRowParam(htmlRow, "isReadonly", lineRef.isReadonly == 1);
        var isHidden = setRowParam(htmlRow, "isHidden", lineRef.isHidden == 1);
        var hasAutoSum = setRowParam(htmlRow, "hasAutoSum", lineRef.hasAutoSum == 1);
        var isCalculate = setRowParam(htmlRow, "isCalculate", lineRef.isCalculate ==1);

        var sTypeLine = setRowParam(htmlRow, "rType", lineRef.type);
        var sRowId = setRowParam(htmlRow, "data-id", lineRef.id);
        var sRowCode = setRowParam(htmlRow, "data-code", lineRef.code);

        // Affichage du Group et de la Ligne
        htmlRow.find('th').attr('title', lineRef.description);
        htmlRow.find('th').prepend(lineRef.name);

    } else {
        var isReadonly = getRowParam(htmlRow, "isReadonly");
        var isHidden = getRowParam(htmlRow, "isHidden");
        var hasAutoSum = getRowParam(htmlRow, "hasAutoSum");
        var isCalculate = getRowParam(htmlRow, "isCalculate");

        var sTypeLine = getRowParam(htmlRow, "rType");
        var sRowId = getRowParam(htmlRow, "data-id");
        var sRowCode = setRowParam(htmlRow, "data-code");

    }

    if (window.isReportReadOnly || window.isReportLocked) {
        isReadonly = true;  
    } else if (isCalculate) {
        htmlRow.find('i.isCalc').removeAttr("hidden");
    }

    if (!hasAutoSum) htmlRow.find("input[name='TOTAL']").remove();

    vInput = htmlRow.find('input');
    for (var i = 0; i < vInput.length; i++) {
        var htmlInput = $(vInput[i]);

        _colRef = htmlInput.attr("name");

        if (!isNaN(_colRef)) _colRef = (_colRef * 1) + (((_colRef * 1) >= 7) ? year * 100 : ((year * 1) + 1) * 100);
        
        if ((typeof dataValue == 'object') && dataValue.hasOwnProperty(_colRef)) {
            htmlInput.attr("data-id", dataValue[_colRef].id);
            htmlInput.attr("data-value", dataValue[_colRef].value);
            if (isReadonly || dataValue[_colRef].isReadonly == 1) {
                htmlInput.attr("disabled", "");
            } else {
                htmlInput.addClass("dataSaved");
            }
            htmlInput.val(dataValue[_colRef].value);

        } else {
            if (isReadonly) htmlInput.attr("disabled", "");
            htmlInput.attr("data-id", "x");
            htmlInput.attr("data-value", "");
        }
    }
}
