$(document).ready(function(){

    // var chartData = JSON.parse( $("#chart_data").text() );

    // var chart = c3.generate({
    //     bindto: '#chart',
    //     data: chartData
    // });

    $("#load_graphic_btn").click(function(event){

        var startYear = $("#start_year_period").val();
        var endYear = $("#end_year_period").val();
        var programId = $("#program_id").val();

        if(!startYear || !endYear){
            alert("Informe um ano de início e um ano de fim.");
            event.preventDefault();
        }
        else{
            var siteUrl = $("#site_url").val();
            var urlToPost = siteUrl + "/program/coordinator/changeReportPeriod";

            $.post(
                urlToPost,
                {
                    startYear: startYear,
                    endYear: endYear,
                    programId: programId
                },
                function(data){
                    var data = JSON.parse(data);
                    /*chart.load({
                        columns: chartNewData.columns
                    });*/
                }
            );
        }
    });
});