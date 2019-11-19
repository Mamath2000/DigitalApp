function initGraphique() {
    Chart.defaults.global.hover.mode = 'nearest';
    Chart.defaults.global.animation.duration = 2000;
    Chart.defaults.global.animation.easing = 'easeInOutBounce';//'easeInOutExpo';
    Chart.defaults.global.layout.padding = 3;
    Chart.defaults.global.legend.position = 'bottom';
    Chart.defaults.global.legend.fullWidth = true
    Chart.defaults.global.legend.labels.fontSize = 15;
    Chart.defaults.global.title.fontSize = 25;
}

//refreshChart("myChart", 1, localStorage.getItem('UserIdAssociates'), 2018)
function refreshChart(htmlGrpDivId, idGraph, idAssociates, year) {
    //api/associates/19/graphs?year=2018
    
        if (isNaN(idAssociates)) return false;
    
        //Web Service URL
        $.ajax({
            type: "GET",
            url: "api/associates/" + idAssociates + "/graphs?graph=" + idGraph + "&year=" + year,
            headers: { "jwt": localStorage.getItem('jwt') },
            contentType: "application/json"
        })
        .done(function (data, text, jqxhr) {
            goChart(htmlGrpDivId, data.data, data.data.associates.firstname + " " + data.data.associates.name , year);
        })
        .fail(function (jqxhr) {
            // show error to console
            sendAlert(jqxhr.statusText, 'alert-danger', jqxhr);
    
        });
    }

function goChart(htmlGrpDivId, graphData, name, year) {
    if (window.myMixedChart) window.myMixedChart.destroy();
    try{ 
        var ctx = $('#'+htmlGrpDivId).get(0).getContext('2d');
        //var ctx = document.getElementById().getContext('2d');
     }catch(e){ 
         console.log('We have encountered an error: ' + e);
         return false;
     }
    window.myMixedChart = new Chart(ctx, {
        type: 'bar',
        data: graphData,
        options: {
            responsive: true,
            title: { display: true, text: 'Evolution de la Trésorerie de ' + name + ' pour l\'année ' + year},
            tooltips: {mode: 'index',intersect: true},
            scales: {
                yAxes: [{stacked: true,gridLines: {display: true,color: "rgba(255,99,132,0.2)"}}],
                xAxes: [{stacked: true,gridLines: {display: false}}]
            }
        }
    }); 
}



