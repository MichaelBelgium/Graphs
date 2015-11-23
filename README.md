# Graphs
Mostly used for a SA:MP server or anything else.

<h1>Information</h1>
These are files that show several stats by using the Pingdom API or SACNR API. I used these 2 API'S for showing the stuff about (a) SA:MP server(s). 

<h1>Chart API's</h1>
The first API i used to create the charts was Google Charts, however I found another and easier one called Highcharts which was extremely easier to use and make. And you have the choice too:
* The files with prefix "google_" are the ones with Google Charts API
* The files with prefix "highcharts_" are the ones with Highcharts API

<h1>Configurations</h1>

This doesn't work in 1, 2, 3...

<h2>Uptime files</h2>

```PHP
define("EMAIL", "");
define("PASS", "");
define("API_KEY", "");
define("CHECK_ID", 0);
```

For the pingdom API authentication is required for using their API. More info <a href="https://www.pingdom.com/resources/api">here</a>.
The ```CHECK_ID``` is an ID of your up- and downtime check by Pingdom. An easy way is to go to your pubic page of the check (Example: ```http://stats.pingdom.com/<something unique>/<here is a check id>```

<h2>Playerbase files</h2>
```PHP
define("SERVER_ID", 0);
define("PAST_HOURS", 12);
```

These files are using the SACNR Monitor API, used to get players from a SA:MP server. Here you need a server id which is easy to get if you search your own server on their <a href="http://monitor.sacnr.com">website</a>. When you land on the page the link looks like this: ```http://monitor.sacnr.com/server-<a server id>.html```. The ```PAST_HOURS``` is a value/the amount of hours you want to go back in time to show the playerbase of your server.

<h1>Dependencies</h1>
If you're missing a file or so;

* <a href="http://monitor.sacnr.com/api.html">SACNRMonitor.php</a>
* <a href="http://github.com/aterrien/jQuery-Knob/blob/master/dist/jquery.knob.min.js">knob.js</a>
* <a href="http://www.highcharts.com/download">highcharts</a>

The rest should be self-explanatory

<h1>Examples</h1>
<a href="http://pat.exp-gaming.net/graph.php?table=false">(Google) Here an example with the knob</a><br>
<a href="http://pat.exp-gaming.net/graph.php">(Google)  Here an example with the table (default)</a>

