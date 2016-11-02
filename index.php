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
    echo "<a href='..' style='font-size: 0.4em;'>back home</a>\n";
}

if ($numeric_id) {
    # verify use entered a valid stop
    include("stopIDs.php");
    $validStop = in_array($stop, $stopIDs);
    if ($validStop) {
        # display stop at top
        echo "<h4>#$stop</h4>\n";

        # valid stop ID, grab the latest info
        $xmlFile = "http://www.corvallistransit.com/rtt/public/utility/file.aspx?contenttype=SQLXML&Name=RoutePositionET.xml&PlatformNo=$stop";
        $xml = simplexml_load_string(file_get_contents($xmlFile));

        # track when the next bus is coming
        $minEta = 30;

        # check if any busses are coming
        if (isset($xml->Platform->Route)) {
            # iterate through each bus and display info
            foreach ($xml->Platform->Route as $routeObj) {
                $routeArr = (array) $routeObj;
                $routeNum = $routeArr["@attributes"]["RouteNo"];

                $tripArr = (array) $routeObj->Destination->Trip;
                $eta = $tripArr["@attributes"]["ETA"];
                if ($eta < $minEta) $minEta = $eta;
                echo "<h2>Rt $routeNum - $eta min</h2>\n";
            }
            echo "<a href='/~gillenp/bus' style='font-size: 0.4em;'>Choose a different stop</a>\n";
        } else {
            date_default_timezone_set('America/Los_Angeles');
            if (date('w') == 0) {
                echo "<h1>Busses don't run on Sunday ya doofus</h1>\n";
            } else {
                echo "<h1>No busses in next 30 minutes</h1>\n";
                echo "<h3><a href='/~gillenp/bus'>Pick another stop?</a></h3>\n";
            }
        }

        # refresh page to quickly get new results
        $refreshRate = ($minEta < 4) ? 15 : 30;
        echo "<meta http-equiv='refresh' content='$refreshRate'/>\n";
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
