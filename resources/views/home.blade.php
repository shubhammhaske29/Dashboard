@extends('layouts.app')

@section('contentheader_title')
@endsection

@section('main-content')
<?php
date_default_timezone_set('Asia/Kolkata');
?>
    <div class="container-fluid spark-screen">
       
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border text-center">
                        <div>
                            <h3 class="box-title">
                            Intra-Day Load Forecast vs Actual Load for Date <?php echo date("d-m-Y");?>
                            </h3>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm btnresponsive" id="intraDayForecastDownload" >Download</button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body" id="Intra_Day_Graph">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Accuracy Summary</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body with-border">
                    <h6 class="box-title">
                      <img src="{{ asset('/img/red.png') }}" width="15px" height="15px">
                      MAE(MW) is above 57
                    </h6>
                    <div id="Accuracy_Summary_Intra_Day_Table" class="fixed-table-container" style="height: 160px;">
                         
                    </div>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
            </div>
        </div>    
    </div>
<script type="text/javascript">
$(window).load(function() {
intraDayAccuracySummaryTable();
intraDayGraph();

//intra day forecast Download
var startDate = subtractDays(new Date(), 1);
var endDate = addDays(new Date(), 2);

intraDayUrl = "{{ route('get-intra-day-forecast',[':strtdate',':enddate']) }}";
intraDayUrl = intraDayUrl.replace(':strtdate', startDate);
intraDayUrl = intraDayUrl.replace(':enddate', endDate);
$("#intraDayForecastDownload").click(function(){
    $.ajax({
        url: intraDayUrl,
        type: 'GET',
        success: function(response)
        {
            downloadForecast('Intra Day Forecast',response);
        }
    });
});

setInterval(function(){
intraDayAccuracySummaryTable();    
intraDayGraph();
}, 60000*14);
});

//intra day accuracy summary table
function intraDayAccuracySummaryTable()
{
jQuery.ajax({
    url: "{{ route('get-intra-day-accuracy-summary') }}",
    method: "GET",
    sync:true,
}).done(function(response) {
    contents = '<table>';
    contents += '<thead>';
    contents += '<tr>';
    contents += '<th>Time Block/Time Period</th>';
    contents += '<th>Mean Absolute Error (MAE)</th>';
    contents += '<th>Average Load (MW)</th>';
    contents += '</tr>';
    contents += '</thead>';
    contents += '<tbody>';
    if(response.status == "success")
    {
        if(response.data.mae_4>57)
        {
        contents += '<tr>';
	contents += '<td style="background: #f2dede;">' +'Last 4 time blocks' + '</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.mae_4).toFixed(2); + '</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.avg_load_4).toFixed(2); + '</td>';
        contents += '</tr>';
        }
        else
        {
	contents += '<tr>';
	contents += '<td style="background: #e9ecef;">' +'Last 4 time blocks' + '</td>';
        contents += '<td>' +(response.data.mae_4).toFixed(2); + '</td>';
        contents += '<td>' +(response.data.avg_load_4).toFixed(2); + '</td>';
        contents += '</tr>';           
        }

        if(response.data.mae_12>57)
        {
        contents += '<tr >';
	contents += '<td style="background: #f2dede;">' +'Last 12 time blocks' + '</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.mae_12).toFixed(2); + '</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.avg_load_12).toFixed(2);+ '</td>';
        contents += '</tr>';
        }
        else
        {
        contents += '<tr>';
	contents += '<td style="background: #e9ecef;">' +'Last 12 time blocks' + '</td>';
        contents += '<td>' +(response.data.mae_12).toFixed(2); + '</td>';
        contents += '<td>' +(response.data.avg_load_12).toFixed(2);+ '</td>';
        contents += '</tr>';            
        }
        
        if(response.data.mae_96>57)
        {
        contents += '<tr >';
    	contents += '<td style="background: #f2dede;">' +'Last 96 time blocks' + '</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.mae_96).toFixed(2); +'</td>';
        contents += '<td style="background: #f2dede;">' +(response.data.avg_load_96).toFixed(2);+ '</td>';
        contents += '</tr>';
        }
        else
        {
        contents += '<tr>';
	contents += '<td style="background: #e9ecef;">' +'Last 96 time blocks' + '</td>';
        contents += '<td>' +(response.data.mae_96).toFixed(2); +'</td>';
        contents += '<td>' +(response.data.avg_load_96).toFixed(2);+ '</td>';
        contents += '</tr>';            
        }
    }
    contents += '</tbody>'; 
    contents += '<table>';  
    $('#Accuracy_Summary_Intra_Day_Table').html(contents); 
    fixTable(document.getElementById('Accuracy_Summary_Intra_Day_Table'));
});
}
//actual vs intra day forecast graph
function intraDayGraph()
{
    jQuery.ajax({
        url: "{{ route('get-intra-day-graph') }}",
        method: "GET",
        sync:false,
    }).done(function(response) {
        if(response.status == "success"){
            var drawalActual = response.data.DrawalActual[0]['data'];
            var drawalActual = drawalActual.slice(0,-1);
            var intradayforecast = response.data.IntraDayForecast[0]['data'];
            Highcharts.setOptions({global: { useUTC: false } });

            new Highcharts.StockChart( {
                        "chart": {
                            "renderTo": "Intra_Day_Graph"
                        }
                        , "title": {
                            "text": ""
                        }
                        , "credits": {
                            "enabled": false, "text": "BYPL Dashboard"
                        }
                        , "lang": {
                            "noData": "The plot for this day could not be generated due to erroneous data or missing data "
                        }
                        , "noData": {
                            "style": {
                                "fontWeight": "bold", "fontFamily": "Times New Roman", "fontSize": "15px", "color": "#FFFFFF"
                            }
                            , "position": {
                                "verticalAlign": "top"
                            }
                        }
                        , "rangeSelector":{
			    enabled:false,
                            buttons: [
                                {
                                    type: 'day',
                                    count: 2,
                                    text: '2d'
                                }],
                            selected : 0,
			    
                            inputEnabled:false
                        }
                        , xAxis: [{
                         type: "datetime",
                         dateTimeLabelFormats: 
			 {
                          month: "%e. %b",
                          year: "%b  %y"
                	 },
                	 tickInterval: 36e5,
                	 minTickInterval: 36e5,
                	 title: {
                    	 text: "Date time"
                	 }
            		}]
                        , "yAxis":[ 
			{ // Primary yAxis
			  opposite: false,
			  showFirstLabel: true,
                          showLastLabel: true,
                	  labels: {
                	     format: '{value:.2f}',
                	     style: {
                	        color: "#7cb5ec"
                	     }
                	  },
                	  title: {
                	     text: 'Load (MW)',
                	     style: {
                	        color: "#7cb5ec"
                	     }
                	  }
               		}], "scrollbar": {
                            "enabled": false
                        }
                        , tooltip: {
			             split:false,
                         shared: true      
        	        },
                        exporting: {
                            enabled: true
                        }
                        , "legend": {
                            "enabled": true, "align": "center", "layout": "horizontal", "margin": 0, "verticalAlign": "bottom"
                        }
                        , "series":[
			{
                          "type": "line", "name": "Intra Day Forecast (MW)", "data": intradayforecast, "lineWidth": 2
                        },
			{
                          "type": "line", "name": "Actual (MW)", "data": drawalActual, "lineWidth": 2
                        }
			/*{
                          "type": "line", "name": "Schedule (MW)", "data": scheduleActual, "lineWidth": 2
                        },
			{
                          "type": "column", "name": "OD/UD (MW)", "yAxis": 1, "data": odud, "lineWidth": 2,"color":"#FF0000"
                        }*/
                        ]
                    },function(chart){
                        // apply the date pickers
                        setTimeout(function () {
                            var actual = chart.series.length - 2;
                            var act = chart.series[actual].points.length;
                            var actual_point = chart.series[actual].points[act - 1];
                            actual_point.update({
                                marker: {
                                    symbol: 'square',
                                    fillColor: "#f50000",
                                    lineColor: "A0F0",
                                    enabled:true,
                                    radius: 5
                                }
                            });
                        }, 0);
                    }
            );
        }
    });
}

function downloadForecast(type,data)
{
    var arrData = typeof data != 'object' ? JSON.parse(data) : data;

    var sheets = [];
    var sheetData = [];
    var tempSheetData = [];
    var firstDate = getDateInStandardFormat(arrData.data.data[0][0]);
    var tempDate = firstDate;
    sheets.push({"sheetid": firstDate, "header": true});
    for(var k = 0 ; k < arrData.data.data.length; k++)
    {
        var dateString = getDateInStandardFormat(arrData.data.data[k][0]);
        var timeString = getTimeInStandardFormat(arrData.data.data[k][0])
        if(type === "Intra Day Forecast") {
           //when date change
            if (tempDate !== dateString) {
                sheets.push({"sheetid": dateString, "header": true});
                tempDate = dateString;
                if (tempSheetData.length > 0) {
                    sheetData.push(tempSheetData);
                    tempSheetData = [];
                }
            }
            //when last date
            if(k === (arrData.data.data.length-1)){
                sheetData.push(tempSheetData);
                tempSheetData = [];
            }
            //push current temp data to array
            tempSheetData.push({
                "Date": dateString,
                "Time-Block": tempSheetData.length + 1,
                "Time-Block Description": timeString,
                "Forecast": arrData.data.data[k][1]
            });
        }
    }
    var opts = [{sheetid:'One',header:true},{sheetid:'Two',header:true}];
    var res = alasql('SELECT INTO XLSX("'+type.split(' ').join('_')+'Report.xlsx",?) FROM ?',[sheets,sheetData]);
}

</script>
@endsection

@include('common')

