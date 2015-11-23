<?php
    /* UPDATE 24/12/2014:
     * Fixed the using_table part
     * Added another table using more server info from SACNR monitor
     * 
     * UPDATE 13/02/2014:
     * Improved variable usage
	 *
 	 * UPDATE 17/04/2014:
 	 * When setting using_table to false it will show an animated knob instead. (https://github.com/aterrien/jQuery-Knob)
 	 * An optional get parameter added for either using a table or displaying the knob
     * */

	include "SACNRMonitor.php";

	$serverid = 1638985;    // SACNR Server ID | http://monitor.sacnr.com/server-<here is an id>.html
	$hours = 24;            // how much past hours should the graph show the playerbase. Max value = 44 !
	$using_table = (isset($_GET["table"]) && $_GET["table"] == "false") ? false : true;    //Display a detailed table under it ? true or false

	$monitor = new SACNRMonitor;
    $json_query = json_encode((array)$monitor->get_query_by_id($serverid));
    $json_info = json_encode((array)$monitor->get_info_by_id($serverid));

	if($hours > 44 || $hours < 1) die("Invalid value: $hours");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>SA:MP Server Graph</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script src="js/knob.js"></script>
		<script type="text/javascript">
		google.load('visualization', '1.0', {'packages':['corechart','table']});
		google.setOnLoadCallback(drawChart);

		function drawChart()
		{
			var data = [], enabled =  <?php echo empty($using_table) ? 0 : 1 ?>, datavalues = <?php echo $json_query ?>, date = [];

            data[0] = new google.visualization.DataTable();
            data[0].addColumn('string', 'Date');
            data[0].addColumn('number', 'Playercount');

			for (var i = datavalues.length-(<?php echo $hours ?>* 4), j = datavalues.length; i < j; i++)
			{
				date[0] = new Date(parseInt(datavalues[i]["Timestamp"])*1000);
				date[1] = ((date[0].getDate() < 10) ? "0"+date[0].getDate() : date[0].getDate()) + "/" + (date[0].getMonth()+1) + "/" + date[0].getFullYear();
				date[2] = date[0].getHours() + ":" + ((date[0].getMinutes() < 10) ? "0"+date[0].getMinutes() : date[0].getMinutes());

				data[0].addRow([date[1] + " " + date[2], parseInt(datavalues[i]["PlayersOnline"])]);
			}

            data[1] = new google.visualization.DataTable(), datavalues = <?php echo $json_info ?>;

            data[1].addColumn('string','Info');
            data[1].addColumn('string','Value');

            data[1].addRows([
                ['Map', datavalues["Map"]],
                ['Players', datavalues["Players"] + "/" + datavalues["MaxPlayers"]],
                ['Average', datavalues["AvgPlayers"]],
                ['Time', datavalues["Time"]],
                ['Version', datavalues["Version"]],
                ['Website', '<a href="http://'+ datavalues["WebURL"] +'">Click</a>'],
                ['Password', (datavalues["Password"] === '0') ? 'No' : 'Yes'],
                ['Host-tab', (datavalues["HostedTab"] === '0') ? 'No' : 'Yes']
            ]);

			if(enabled === 1)
			{
                var table = new google.visualization.Table(document.getElementById('table'));
                table.draw(data[0], {showRowNumber: true, width: 600});
                table = new google.visualization.Table(document.getElementById('info'));
                table.draw(data[1], {showRowNumber: false, width: 400, allowHtml: true});
			}
			else
			{
				$("#table").knob({
					'readOnly': true,
					'max': datavalues["MaxPlayers"],
				});

				 $({players: 0}).animate(
				 	{players: datavalues["Players"]}, {
				      	duration: 8000,
				      	easing:'swing', 
				      	progress: function() { 
				        	$("#table label").text(Math.round(this.players) + "/" + datavalues["MaxPlayers"]);
				        	$("#table").val(this.players).trigger('change');
				      	}
				  });
			}

			var options = {
				title:  datavalues["Hostname"] + " (" + datavalues["Gamemode"] + ")",
				height: 400,
				legend: {position: 'bottom'},
				orientation: 'horizontal'
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart'));
			chart.draw(data[0], options);
		}
	    </script>
        <style type="text/css">
            #table, #info { display: inline-block;  vertical-align: top;}
            #table label { font-size: 30px; }
            #chart { margin: 10px 0; }
        </style>
	</head>
	<body>
		<div id="chart"></div>
		<div id="table"><label></label></div>
        <div id="info"></div>
	</body>
</html>
