<?php
    include 'monitor.class.php';

    define("SERVER_ID", 1788032);
    define("PAST_HOURS", 12);

    $monitor = new SACNR\Monitor;
    $json_query = json_encode((array)$monitor->get_query_by_id(SERVER_ID));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Playerbase</title>
    </head>
    <body>
        <section id="graph"></section>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var data = <?php echo $json_query ?>, hours = [], players = [], date;
                
                for (var i = data.length - (<?php echo PAST_HOURS; ?>*4), j = data.length; i < j; i++) {
                    date = new Date(parseInt(data[i]["Timestamp"])*1000);
                    hours.push(date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear() + " " + date.getHours() + ":" + ((date.getMinutes() < 10) ? "0"+date.getMinutes() : date.getMinutes()));

                    players.push(parseInt(data[i]["PlayersOnline"]));
                };

                $('#graph').highcharts({
                    title: {
                        text: 'Playerbase',
                        x: -20
                    },
                    subtitle: {
                        text: 'Player history',
                        x: -20
                    },
                    xAxis: { categories: hours },
                    yAxis: {
                        title: { text: 'Players' },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                        name: 'Players',
                        data: players
                    }]
                });
            });
        </script>
    </body>
</html>
