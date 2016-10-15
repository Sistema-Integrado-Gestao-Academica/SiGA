$(document).ready(function(){

    var chartData = JSON.parse( $("#chart_data").text() );

    var chart = c3.generate({
        bindto: '#chart',
        data: chartData
    });


    $("#load_graphic_btn").click(function(event){

        changeChart(chart);
        
    });

});

function changeChart(chart){

    var startYear = $("#start_year_period").val();
    var endYear = $("#end_year_period").val();
    var programId = $("#program_id").val();

    if(!startYear || !endYear){
        alert("Informe um ano de início e um ano de fim.");
        event.preventDefault();
    }
    else{
        var siteUrl = $("#site_url").val();
        var urlToChangeChart = siteUrl + "/program/coordinator/changeChart";

        $.post(
            urlToChangeChart,
            {
                startYear: startYear,
                endYear: endYear,
                programId: programId
            },
            function(data){
                var receivedData = JSON.parse(data);
                var chartNewDataEncoded = receivedData.chartData;
                var chartNewData = JSON.parse(chartNewDataEncoded);
                chart.load({
                    columns: chartNewData.columns
                });
                var collaborationIndicators = receivedData.collaborationIndicators;
                changeCollaborationTable(collaborationIndicators, siteUrl);
            }
        );

    }
}

function changeCollaborationTable(collaborationIndicators, siteUrl){

    urlToChangeCollaborationTable = siteUrl + "/program/coordinator/changeCollaborationTable";
    $.post(
        urlToChangeCollaborationTable,
        {
            collaborationIndicators: collaborationIndicators
        },
        function(data){
            $('#collaboration_indicator_table').html(data);
        }
    );

}