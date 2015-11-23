<?php
 	define("EMAIL", "michaelvrld@gmail.com");
 	define("PASS", "KIRSTEN2");
 	define("API_KEY", "4ndf0et5cy9tjd8x9bj409xk90f3mp1m");
  	
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.pingdom.com/api/2.0/summary.performance/1530487?includeuptime=true&resolution=day");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_USERPWD, EMAIL.":".PASS);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("App-Key: ".API_KEY));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
    $response = json_decode(curl_exec($curl),true);

    if (isset($response['error'])) {
        print "Error: " . $response['error']['errormessage'] . "\n";
        exit;
    }
    
    $list = json_encode($response["summary"]["days"]);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Uptime</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    </head>
    <body>
        <section id="graph"></section>

        <script type="text/javascript">
            google.load("visualization", "1.1", {packages:["corechart","table"]});
            google.setOnLoadCallback(drawChart);

            function drawChart () 
            {
                var json = <?php echo $list; ?>, options, data;
                data = new google.visualization.DataTable();
                data.addColumn('string', 'Date');
                data.addColumn('number', 'Uptime');
                data.addColumn('number', 'Downtime');
                data.addColumn('number', 'Average response');

                for (var i = 0; i < json.length; i++) data.addRow([new Date(json[i].starttime*1000).toDateString(), (json[i].uptime / 86400) * 100, (json[i].downtime / 86400) * 100, json[i].avgresponse ]);

                options = {
                    title : 'Up/downtime and average response',
                    vAxis: {title: "Procent/ms", minValue: 0},
                    hAxis: {title: "Day"},
                    seriesType: "bars",
                    height: 500,
                    series: {2: {type: "line"}},
                    isStacked: true,
                    colors: ['#4AC23A', '#dc3912', 'orange']
                };

                var formatter = new google.visualization.NumberFormat({pattern: '0.00'})
                formatter.format(data, 1);
                formatter.format(data, 2);

                var chart = new google.visualization.ComboChart(document.getElementById('graph'));
                chart.draw(data, options);
            }   
        </script>
    </body>
</html>
