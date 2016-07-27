<html>
<head>
<link rel="SHORTCUT ICON" HREF="bus.png">
<meta http-equiv="refresh" content="15"/>
<style>body {font-size: 45pt;}</style>
<?php
    # verify URL has id parameter
    if (!isset($_GET["id"])) die("Please enter an id.");

    $stop = $_GET["id"];
    echo "<title>$stop</title>\n";
?>
</head>
<body><center>
<?php
    # display stop at top
    echo "<h4>#$stop</h4>\n";

    # grab the latest info
    $xmlFile = "http://www.corvallistransit.com/rtt/public/utility/file.aspx?contenttype=SQLXML&Name=RoutePositionET.xml&PlatformNo=$stop";
    $xml = simplexml_load_string(file_get_contents($xmlFile));

    # check if any busses are coming
    if (isset($xml->Platform->Route)) {
        # iterate through each bus and display info
        foreach ($xml->Platform->Route as $routeObj) {
            $routeArr = (array) $routeObj;
            $routeNum = $routeArr["@attributes"]["RouteNo"];

            $tripArr = (array) $routeObj->Destination->Trip;
            $eta = $tripArr["@attributes"]["ETA"];

            echo "<h2>Rt $routeNum - $eta min</h2>\n";
        }

    } else {
        echo "<h1>No busses in next 30 minutes</h1>\n";
    }
?>
</center></body>
</html>
