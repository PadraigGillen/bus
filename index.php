<html>
<head>
    <link rel="SHORTCUT ICON" HREF="https://upload.wikimedia.org/wikipedia/commons/8/80/TransLink-op-head-bus-right.png">
    <meta http-equiv="refresh" content="15"/>
    <style>
        body{
            font-size: 45pt;
        }
    </style>
    <?php
    if (!isset($_GET["id"])) {
        die("Please enter an id.");
    }
    $stop = $_GET["id"];
    echo "<title>$stop</title>\n";
?>
</head>
<body>
    <center>
<?php

    #$xmlFile = "sample.xml";
    $xmlFile = "http://www.corvallistransit.com/rtt/public/utility/file.aspx?contenttype=SQLXML&Name=RoutePositionET.xml&PlatformNo=$stop";
    $xml = simplexml_load_string(file_get_contents($xmlFile));

    if (isset($xml->Platform->Route)) {
        $trip_arr = (array) $xml->Platform->Route->Destination->Trip;
        $eta = $trip_arr["@attributes"]["ETA"];
        
        $route_arr = (array) $xml->Platform->Route;
        $route = $route_arr["@attributes"]["RouteNo"];

    } else {
        $eta = "&gt; 30";
        $route = "unknown";
    }

    echo "        <h4>$stop - Route $route</h4>\n";
    echo "        <h1><b>$eta min</b></h1>\n";

?>
    </center>
</body>
</html>
