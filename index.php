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
        $stop = 2;
        #die("Please enter an id.");
    }
    $stop = $_GET["id"];
    echo "<title>$stop</title>\n";
?>
</head>
<body>
<center>
<?php
    echo "<h4>#$stop</h4>\n";
    #$xmlFile = "sample.xml";
    $xmlFile = "http://www.corvallistransit.com/rtt/public/utility/file.aspx?contenttype=SQLXML&Name=RoutePositionET.xml&PlatformNo=$stop";
    $xml = simplexml_load_string(file_get_contents($xmlFile));

    if (isset($xml->Platform->Route)) {
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
</center>
</body>
</html>
