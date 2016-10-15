var charts = [];

$(document).ready(function(){

    var chartsData = JSON.parse( $("#chart_data").text() );

    for(var program in chartsData){
        charts[program] = c3.generate({
            bindto: '#program_' + program + '_chart',
            data: chartsData[program]
        });
    }
});

function updateProgramProductionsChart(event, program){

    var year = $("#report_year_" + program).val();

    if(!year){
        alert("Informe um ano para a pesquisa.");
        event.preventDefault();
    }else{
        var siteUrl = $("#site_url").val();
        var urlToPost = siteUrl + "/program/productionManagement/changeReportYear";

        $.post(
            urlToPost,
            {year: year, program: program},
            function(data){
                var chartNewData = JSON.parse(data);
                charts[program].load({
                    columns: chartNewData.columns
                });
            }
        );
    }
}