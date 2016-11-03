<?php
    # verify URL has valid parameter
    $numeric_id = false;
    if ( isset($_GET["id"]) && ctype_digit($_GET["id"]) ) {
        $numeric_id = true;
        $stop = $_GET["id"];
    }
?>
<html>
<head>
<link rel="SHORTCUT ICON" HREF="bus.png">
<style>
main {
    font-size: 3em;
    text-align: center;
}
.footnote {
    font-size: 0.4em;
}
</style>
<title><?php echo ($numeric_id) ? $stop : "All aboard";?></title>
</head>
<main>
<?php
function displayStopInputForm() {
    # show user a form to enter their stop ID
    echo "<h4>Please enter your stop ID</h4>\n";
    echo "<form method='get'><input type='number' name='id' required autofocus><input type='submit'></form>\n";
    echo "<a href='..' class='footnote'>back home</a>\n";
}

if ($numeric_id) {
    # verify use entered a valid stop
    include("stopIDs.php");
    if (in_array($stop, $stopIDs)) { // check if stop is in our list
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
            echo "<a href='/~gillenp/bus' class='footnote'>Choose a different stop</a>\n";
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
</main>
</html>
