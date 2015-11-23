<?php
 	define("EMAIL", "");
 	define("PASS", "");
 	define("API_KEY", "");
    define("CHECK_ID", 0);
 	
 	
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.pingdom.com/api/2.0/summary.performance/".CHECK_ID."?includeuptime=true&resolution=day");
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
    </head>
    <body>
        <section id="graph"></section>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript">
            $(document).ready( function() {
                var json = <?php echo $list; ?>, dates = [], uptime = [], downtime = [], response = [];

                for (var i = 0; i < json.length; i++)
                {
                    dates.push(new Date(json[i].starttime*1000).toDateString());
                    uptime.push((json[i].uptime / 86400) * 100);
                    downtime.push((json[i].downtime / 86400) * 100);
                    response.push(json[i].avgresponse);
                }

                $("#graph").highcharts({
                     title: { text: 'Server uptime' },
                    subtitle: { text: 'Server uptime, downtime and response time of the last few days' },
                    xAxis: { categories: dates },
                    yAxis: {
                        labels: { format: '{value} %' },
                        min: 0,
                        max: 100,
                        title: {
                            text: "Down- and uptime amount"
                        }
                    },
                    plotOptions: { series: { stacking: 'normal' } },
                    series: [{
                        type: "column",
                        name: 'Uptime',
                        data: uptime,
                        tooltip: { valueSuffix: ' %' }
                    }, {
                        type: 'column',
                        name: 'Downtime',
                        data: downtime,
                        tooltip: { valueSuffix: ' %' }
                    }, {
                        type: 'spline',
                        name: "Avg response",
                        data: response,
                        tooltip: { valueSuffix: ' ms' }
                    }]
                });
            });            
        </script>
    </body>
</html>
