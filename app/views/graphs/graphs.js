$(document).ready(function () {

    $(document).on('click', '#btnRefreshDataGraph', function (e) {
        onGraphComboLoading();
    });

    $(document).on('change', '#forGraphComboAssociates', function (e) {
        idAssociates = $(e.currentTarget).val();
        // Load Year Combo
        loadCombo($("#forGraphComboYear"), "idAssociates/" + idAssociates + "/fulltimelines", "", true, "", onGraphComboLoading);
    });

});

// Call by app.js when page is charge.
function onloadGraphsScreen(GraphId) {

    $("#idGraphs").val(GraphId);

    idAssociates = localStorage.getItem('UserIdAssociates');

    urlParam = "?idGraphs=" + GraphId;

    // Load Year Combo
    if (!isNaN(idAssociates)) {
        loadCombo($("#forGraphComboYear"), "idAssociates/" + idAssociates + "/fulltimelines", "", true);    
    }

    // Load Assciates Combo
    loadCombo($("#forGraphComboAssociates"), "idAssociates",((isAdmin) ? "" : (isNaN(idAssociates) ? "" : idAssociates)), false, []);
    if (!isAdmin()) {
        $("#forGraphComboAssociates").val(localStorage.getItem('UserIdAssociates'));
        $("#forGraphComboAssociates").attr("disabled",true);
    } else {
        $("#forGraphComboAssociates").removeAttr("disabled");
    }

    initGraphique();

    //refreshChart("myChart", 1, localStorage.getItem('UserIdAssociates'), new Date().getFullYear());
};

function onGraphComboLoading() {


    crit = $('#dataGraphSearchForm').serializeObject();
    if (!crit.idAssociates ||!crit.year || !crit.idGraphs) return null;

    refreshChart("myChart", crit.idGraphs, crit.idAssociates, crit.year);
}


