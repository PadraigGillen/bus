<html>
<head>
<link rel="SHORTCUT ICON" HREF="bus.png">
<style>body {font-size: 3em;}</style>
<?php
    $numeric_id = false;
    # verify URL has id parameter
    if ( isset($_GET["id"]) && ctype_digit($_GET["id"]) ) {
        $numeric_id = true;
        $stop = $_GET["id"];
        echo "<title>$stop</title>\n";
    } else { # user is new or entered a bad ID
        echo "<title>All aboard</title>\n";
    }
?>
</head>
<body><center>
<?php
function displayStopInputForm() {
    # show user a form to enter their stop ID
    echo "<h4>Please enter your stop ID</h4>\n";
    echo "<form method='get'><input type='number' name='id' required autofocus><input type='submit'></form>\n";
}

if ($numeric_id) {
    # verify use entered a valid stop
    include("stopIDs.php");
    $validStop = in_array($stop, $stopIDs);
    if ($validStop) {
        # refresh page to quickly get new results
        echo "<meta http-equiv='refresh' content='15'/>\n";

        # display stop at top
        echo "<h4>#$stop</h4>\n";

        # valid stop ID, grab the latest info
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
            echo "<a href='/~gillenp/bus' style='font-size: 0.4em;}'>Choose a different stop</a>\n";
        } else {
            echo "<h1>No busses in next 30 minutes</h1>\n";
            echo "<h3><a href='/~gillenp/bus'>Pick another stop?</a></h3>\n";
        }
    } else {
        echo "<h3>-Invalid stop entered-</h3>\n";
        displayStopInputForm();
    }
} else {
    displayStopInputForm();
}
?>
</center></body>
</html>
