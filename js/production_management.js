$(document).ready(function(){

    var chartData = JSON.parse( $("#chart_data").text() );

    var chart = c3.generate({
        bindto: '#chart',
        data: chartData
    });

    $("#load_graphic_btn").click(function(event){

        var year = $("#report_year").val();

        if(!year){
            alert("Informe um ano para a pesquisa.");
            event.preventDefault();
        }else{
            var siteUrl = $("#site_url").val();
            var urlToPost = siteUrl + "/program/productionManagement/changeReportYear";

            $.post(
                urlToPost,
                {year: year},
                function(data){
                    var chartNewData = JSON.parse(data);
                    chart.load({
                        columns: chartNewData.columns
                    });
                }
            );
        }
    });
});