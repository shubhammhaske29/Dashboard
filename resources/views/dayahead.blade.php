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
        <!-- Default box -->
        <div class="box box-info">
           <div class="box-header with-border text-center">
              <h3 class="box-title">
                Day Ahead Load Forecast vs Actual Load for Date <?php echo date("d-m-Y",strtotime("+1 days"));?>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
         <div class="box-body" id="Day_Ahead_Graph">
         </div>
        </div>
     </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">
                    Forecast Reports
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
     <div class="box-body" >
        <table class="table table-bordered table-striped text-center">
            <tbody>
                <tr>
                    <td>Last 3 Days URD Based Forecast</td>
                    <td><button type="button" class="btn btn-primary" id="lastThreeDaysUrdForecastDownload" >Download</button>
                    </td>
                </tr>
                <tr id="sldctr" style="display: none;">
                    <td id="sldcforecastdatetime">SLDC data based Forecast at </td>
                    <td><button type="button" class="btn btn-primary" id="sldcDayAheadForecastDownload" >Download</button></td>
                </tr>
                <tr id="urdtr" style="display: none;" ><!-- style="display: none;" class="urdtr" -->
                    <td id="urdforecastdatetime">URD data based Forecast at </td>
                    <td><button type="button" class="btn btn-primary" id="urdDayAheadForecastDownload" >Download</button></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border text-center">
                <h3 class="box-title">DAY-WISE ACCURACY SUMMARY</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body with-border">
                <h6 class="box-title">
                  <img src="{{ asset('/img/red.png') }}" width="15px" height="15px">
                  MAE(MW) is above 30
              </h6>
              <div id="Accuracy_Summary_Day_Ahead_Table" class="fixed-table-container" style="height: 285px;">

              </div>
          </div>
          <div class="box-footer">
          </div>
      </div>
  </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="box box-info">
     <div class="box-header with-border text-center">
        <h3 class="box-title"><?php echo "UNRESTRICTED DEMAND & FORECAST-TPDDL | " . date('dS M Y',strtotime("-1 days"));?></h3>

    </div>
    <div class="box-body with-border">
        <h6 class="box-title">
          <img src="{{ asset('/img/red.png') }}" width="15px" height="15px">
          MAE(MW) (Actual - Forecast) is above 30
      </h6>
      <h6 class="box-title">
          <img src="{{ asset('/img/blue.png') }}" width="15px" height="15px">
          Forecasted Load (MW) not available as URD data was not available
      </h6>
      <h6 class="box-title">
          <img src="{{ asset('/img/blue.png') }}" width="15px" height="15px">
          MAE(MW) (Actual - Forecast) not calculated as URD data was not available
      </h6>
      <div id="Day_Ahead_Schedule_Forecast_Table" class="fixed-table-container">

      </div>
  </div>
  <div class="box-footer">
  </div>
</div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Weather Combiner</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-primary btn-sm" id="waetherForecastDownload" >Download</button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        <div class="box-body" id="day_ahead_weather_combiner_Graph">
        </div>
        <div class="box-footer">
        </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
$(window).load(function() {
dayAheadGraph();
getCreatedDate();
dayAheadAccuracyTable();
dayAheadScheduleForecastTable();
dayAheadWeatherCombinerGraph();

var datedayahead = addDays(new Date(), 1);
$("#urdDayAheadForecastDownload").click(function(){
    dayAheadUrl = "{{ route('get-urd-day-ahead-forecast-excel',[':strtdate',':enddate']) }}";
    dayAheadUrl = dayAheadUrl.replace(':strtdate', datedayahead);
    dayAheadUrl = dayAheadUrl.replace(':enddate', datedayahead);
    $.ajax({
        url: dayAheadUrl,
        type: 'GET',
        success: function(response)
        {
           downloadForecast('URD Day Ahead Forecast',response.data);
        }
    });
});

$("#waetherForecastDownload").click(function(){
    downloadWaetherForecast();
});

//last 3 days urd forecast
$("#lastThreeDaysUrdForecastDownload").click(function(){
    var startfulldate = subtractDays(new Date(), 3);
    var endfulldate = subtractDays(new Date(), 1);
    dayAheadUrl = "{{ route('get-last-3-days-urd-forecast-excel',[':strtdate',':enddate']) }}";
    dayAheadUrl = dayAheadUrl.replace(':strtdate', startfulldate);
    dayAheadUrl = dayAheadUrl.replace(':enddate', endfulldate);

    console.log(dayAheadUrl)

    $.ajax({
        url: dayAheadUrl,
        type: 'GET',
        success: function(response)
        {

            downloadForecast('Last 3 Days Urd Forecast',response);
        }
    });
});

setInterval(function(){
dayAheadGraph();
dayAheadWeatherCombinerGraph();
}, 60000*5);

setInterval(function(){
getCreatedDate();
dayAheadAccuracyTable();
dayAheadScheduleForecastTable();
}, 60000*15);

});

function getCreatedDate()
{
        var startfulldate = addDays(new Date(), 1);
        var endfulldate = addDays(new Date(), 1);
        dayAheadUrl = "{{ route('get-urd-day-ahead-forecast-excel',[':strtdate',':enddate']) }}";
        dayAheadUrl = dayAheadUrl.replace(':strtdate', startfulldate);
        dayAheadUrl = dayAheadUrl.replace(':enddate', endfulldate);
        jQuery.ajax({
        method: "GET",
        url:dayAheadUrl,
        sync:false,
        }).done(function(response) {
        if(response.status == "success")
            {
                document.getElementById("urdtr").style.display = 'table-row';
                contents = 'URD data based Forecast at '+response.data.created_at;
                $('#urdforecastdatetime').html(contents);
            }
    });
}

function dayAheadWeatherCombinerGraph()
{
    var startDate = subtractDays(new Date(), 1);
    var endDate = addDays(new Date(), 1);
    
    Url = "{{ route('get-weather-combiner-graph',[':strtdate',':enddate']) }}";
    Url = Url.replace(':strtdate', startDate);
    Url = Url.replace(':enddate', endDate);
    
        $.ajax({
            url: Url,
            type: 'GET',
            sync:false,
            success: function(response)
            {
		console.log(response.data.WeatherCombiner[0]['apparent_temperature'].length)
                if (response.data.WeatherCombiner[0]['apparent_temperature'].length>0) 
                {
                    var weatherdata = displayWeatherCombinerGraph('day_ahead_weather_combiner_Graph',response);    
                }
                else
                {
                    contents = '';
                    contents += 'Data for graph is not available';
                    $('#day_ahead_weather_combiner_Graph').html(contents);
                    document.getElementById('waetherForecastDownload').style.display = 'none';
                }
            }
        });
}



//---download forecast function start---
function downloadForecast(type,data)
{
    var arrData = typeof data != 'object' ? JSON.parse(data) : data;
    function pad(n)
    {
        return n<10 ? '0'+n : n
    }
    var sheets = [];
    var sheetData = [];
    var tempSheetData = [];
    if(type === "Last 3 Days Urd Forecast")
    {
        var firstDate = getDateInStandardFormat(arrData.data.urdActual[0]['datetime']);
        var tempDate = firstDate;
        sheets.push({"sheetid": tempDate, "header": true});
        for(var k = 0 ; k < arrData.data.urdActual.length; k++)
        {
            var dateString = getDateInStandardFormat(arrData.data.urdActual[k]['datetime']);

            var timeString = getTimeInStandardFormat(arrData.data.urdActual[k]['datetime']);
            if (tempDate !== dateString) {
                sheets.push({"sheetid": dateString, "header": true});
                tempDate = dateString;
                if (tempSheetData.length > 0) {
                    sheetData.push(tempSheetData);
                    tempSheetData = [];
                }
            }

            tempSheetData.push({
                "Date": dateString,
                "Time-Block": tempSheetData.length + 1,
                "Time-Block Description": timeString,
                "Forecast": arrData.data.urdForecast[k][1],
                "Actual": arrData.data.urdActual[k]['actual']
            });

            //when last date
            if(k === (arrData.data.urdActual.length-1)){
                sheetData.push(tempSheetData);
                tempSheetData = [];
            }

                   
        }
    }
    else
    {
        var firstDate = addDays(new Date(), 1);
        var currentDate = getCurrentDate();

        var tempDate = firstDate;
        for(var k = 0 ; k < arrData.data.length; k++)
        {
            var dateString = getDateInStandardFormat(arrData.data[k][0]);
            var timeString = getTimeInStandardFormat(arrData.data[k][0]);

            if(dateString>currentDate)
            {
                tempSheetData.push({
                "Date": dateString,
                "Time-Block": tempSheetData.length + 1,
                "Time-Block Description": timeString,
                "Forecast": arrData.data[k][1]
                });
                if(k === (arrData.data.length-1)){
                    sheets.push({"sheetid": tempDate, "header": true});
                    sheetData.push(tempSheetData);
                }    
            }
        }    
    }
   var opts = [{sheetid:'One',header:true},{sheetid:'Two',header:true}];
   var res = alasql('SELECT INTO XLSX("'+type.split(' ').join('_')+'Report.xlsx",?) FROM ?',[sheets,sheetData]);
}
//---download forecast function end---



//---dayahead graph start----
function dayAheadGraph()
{
    jQuery.ajax({
        url: "{{ route('get-day-ahead-graph') }}",
        method: "GET",
        sync:false,
    }).done(function(response) {
        if(response.status == "success"){
            var actual = response.data.Actual[0]['data'];
            var actual = actual.slice(0,-1);
            //var sldcForecast = response.data.SldcForecast[0]['data'];
            var urdForecast = response.data.UrdForecast[0]['data'];
            Highcharts.setOptions({global: { useUTC: false } });
            new Highcharts.StockChart( {
                        "chart": {
                            "renderTo": "Day_Ahead_Graph"
                        }
                        , "title": {
                            "text": ""
                        }
                        , "credits": {
                            "enabled": false, "text": "BYPL Dashboard"
                        }
                        ,rangeSelector:
                        {
                            buttons: [
                                {
                                    type: 'day',
                                    count: 2,
                                    text: '2d'
                                }],
                            selected : 0,
                            inputEnabled:false
                        }, "xAxis": {
                            crosshair: {
                                enabled: true
                            }
                        }
                        , "yAxis":[ { 
                        opposite: false,
                        allowDecimals : true,
                        title: {
                            text: "Load (MW)",
                            style: {
                                color: '#89A54E'
                            }
                           },
                        showFirstLabel: true,
                        showLastLabel: true,
                        labels: { align: 'right',
                                    format: '{value:.2f}'
                                },
                        showEmpty: false,
                        }], "scrollbar": {
                            "enabled": false
                        }
                        , "tooltip": {
                            split: false,
                            shared: true,
                            valueDecimals: 2
                        },
                        exporting: {
                            enabled: true
                        }
                        , "legend": {
                            "enabled": true, "align": "center", "layout": "horizontal", "margin": 0, "verticalAlign": "bottom"
                        }
                        , "series":[ {
                            "type": "line", "name": "URD Forecast (MW)", "yAxis": 0,"data": urdForecast, "lineWidth": 2
                        },
                        {
                            "type": "line", "name": "Actual (MW)", "yAxis": 0, "data": actual, "lineWidth": 2
                        }]
                    },function(chart){
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
//---dayahead graph end----



//----Accuracy table start-------
function dayAheadAccuracyTable()
{
    jQuery.ajax({
    url: "{{ route('get-day-ahead-accuracy-summary') }}",
    method: "GET",
    sync:false,
}).done(function(response) {
    if(response.status == "success"){
        contents = '<table>';
        contents += '<thead>';
        contents += '<tr>';
        contents += '<th>Date</th>';
        contents += '<th>Mean Absolute Error (MAE)</th>';
        contents += '<th>Average Load (MW)</th>';
        contents += '</tr>';
        contents += '</thead>';
        contents += '<tbody>';
        for (var i = response.data.length - 1; i >= 0; i--) 
        {
            contents += '<tr >';
            if(response.data[i]['ae']>30)
            {
                contents += '<td style="background: #f2dede;">' +(response.data[i]['date'])+ '</td>';
                contents += '<td style="background: #f2dede;">' +(response.data[i]['ae']).toFixed(2)+ '</td>';
                contents += '<td style="background: #f2dede;">' +(response.data[i]['avg_load']).toFixed(2)+ '</td>';
            }
            else
            {
                contents += '<td>' +(response.data[i]['date'])+ '</td>';
                contents += '<td>' +(response.data[i]['ae']).toFixed(2)+ '</td>';
                contents += '<td>' +(response.data[i]['avg_load']).toFixed(2)+ '</td>';
            }
            contents += '</tr>';
        }
        contents += '</tbody>';
        contents += '</table>';

        $('#Accuracy_Summary_Day_Ahead_Table').html(contents);
        fixTable(document.getElementById('Accuracy_Summary_Day_Ahead_Table'));
    }
});
}
//----Accuracy table end-------



function dayAheadScheduleForecastTable()
{
    jQuery.ajax({
    url: "{{ route('get-day-ahead-schedule-forecast-table') }}",
    method: "GET",
    sync:false,
}).done(function(response) {
    if(response.status == "success")
    {
        if(response.data['Schedule'][0]['data'].length != 0 && response.data['UrdActual'][0]['data'].length != 0)
        {
            contents = '<table>';
            contents += '<thead class="thead-light">';
            contents += '<tr>';
            contents += '<th>Date and Time</th>';
            contents += '<th>Schedule (MW)</th>';
            contents += '<th>Unrestricted Demand (MW)</th>';
            contents += '<th>Forecasted Load (MW)</th>';
            contents += '<th>MAE (MW)</th>';
            contents += '</tr>';
            contents += '</thead>';
            contents += '<tbody>';

            for (var i = 0; i <= response.data['UrdActual'][0]['data'].length - 1; i++) 
            {
                var currentDate = new Date(response.data['UrdActual'][0]['data'][i]['datetime']);
                var datefromtimestamp = response.data['UrdActual'][0]['data'][i]['datetime'];

                urdschedule = response.data['Schedule'][0]['data'][i]['schedule'];
                urdactual = response.data['UrdActual'][0]['data'][i]['actual'];
                urdforecast = (response.data['UrdForecast'][0]['data'][i]['value']  == "Not Available")?'-':response.data['UrdForecast'][0]['data'][i][1];
		if(urdforecast == '-')
		{
		  var mae = '-'
		}
		else
		{
		  var mae = urdactual - urdforecast;
		  var mae = Math.abs(mae).toFixed(2);
		}	
                if(mae!='-' && mae>30)
                {
                    contents += '<tr> ';
                    contents += '<td style="background: #f2dede;">'+datefromtimestamp+'</td>';//.split(' ').join(' | ')+'</td>';
                    contents += '<td style="background: #f2dede;">'+urdschedule+'</td>';
                    contents += '<td style="background: #f2dede;">'+urdactual+'</td>';
                    contents += '<td style="background: #f2dede;">'+urdforecast+'</td>';
                    contents += '<td style="background: #f2dede;">'+mae+'</td>';
                    contents += '</tr>';
                }
                else
                {
                    contents += '<tr> ';
                    contents += '<td>'+datefromtimestamp+'</td>';//.split(' ').join(' | ')+'</td>';
                    contents += '<td>'+urdschedule+'</td>';
                    contents += '<td>'+urdactual+'</td>';
		    if(mae=='-')
		    {
   		    	contents += '<td style="background: #cce6ff;">'+urdforecast+'</td>';
                    	contents += '<td style="background: #cce6ff;">'+mae+'</td>';
 		    }
		    else
		    {
			contents += '<td>'+urdforecast+'</td>';
                    	contents += '<td>'+mae+'</td>';
		    }
                    contents += '</tr>';
                }
            }
            contents += '</tbody>';
            contents += '</table>';
            $('#Day_Ahead_Schedule_Forecast_Table').html(contents);
            fixTable(document.getElementById('Day_Ahead_Schedule_Forecast_Table'));
        }
        else
        {
            contents = '<tr>';
            contents += '<td style="text-align:center;">Some Of Data For Table Is Not Available</td>';
            contents += '</tr>';
            contents += '</tbody>';
            contents += '</table>';
            $('#Day_Ahead_Schedule_Forecast_Table').html(contents);
            document.getElementById("Day_Ahead_Schedule_Forecast_Table").style.height = "40px";
            document.getElementById('day-ahead-schedule-forcast-table-download').style.display = 'none';
        }
    }    
});
}

</script>


@endsection

@include('common')
