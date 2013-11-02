<?php

$date = "20131101";//Ymd

$date = new DateTime();

echo "London Victoria - Mitcham Eastfields \n";
echo $date->format("d-m-Y") . "\n";
$date = $date->format("Ymd");
$data = file_get_contents("http://journeyplanner.tfl.gov.uk/user/XSLT_TRIP_REQUEST2?language=en&execInst=&sessionID=0&ptOptionsActive=1&itOptionsActive=1&imparedOptionsActive=1&ptAdvancedOptions=1&place_origin=London&place_destination=London&show_origin=Victoria&show_destination=mitcham+eastfields&type_origin=stop&type_destination=stop&itdTripDateTimeDepArr=dep&datepicker=Today&stepfree-access=no-requirements&routeType=LEASTTIME&includedMeans=checkbox&inclMOT_2=on&inclMOT_1=on&inclMOT_5=on&inclMOT_0=on&inclMOT_4=on&inclMOT_9=on&inclMOT_7=on&inclMOT_8=on&inclMOT_3=on&trITMOTvalue101=60&trITMOTvalue=20&trITMOT=100&changeSpeed=normal&name_origin=1000248&name_destination=1001555&itdDate=$date&itdTimeHour=18&itdTimeMinute=00");



preg_match_all("/tr class=\"det_hov.*?\/tr\>/", $data, $m);
if (is_array($m)) {
    // var_dump($m);die;
    foreach ($m[0] as $row) {
        $regexp = "<td\s[^>]*>(.*)<\/td>";

        if(preg_match_all("/$regexp/siU", $row, $matches, PREG_SET_ORDER)) {
            print $matches[1][1] . ' - ' . $matches[2][1] . "\n";
        }
    }
}
