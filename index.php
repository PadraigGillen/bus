<html>
<head>
<link rel="SHORTCUT ICON" HREF="bus.png">
<style>body {font-size: 45pt;}</style>
<?php
    # verify URL has id parameter
    if (isset($_GET["id"])) {
        $stop = $_GET["id"];
        echo "<title>$stop</title>\n";
    } else {
        $stop = -1; # yeah, yeah, yeah...
        echo "<title>All aboard</title>\n";
    }
?>
</head>
<body><center>
<?php
if ($stop != -1) {
    # refresh page to quickly get new results
    echo "<meta http-equiv='refresh' content='15'/>\n";

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
} else {
    # show user a form to enter their stop ID
    echo "<h4>Please enter your stop ID</h4>\n";
    echo "<form method='get'><input type='number' name='id' required><input type='submit'></form>\n";
}
?>
</center></body>
</html>
