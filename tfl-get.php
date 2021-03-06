<?php

class Tfl
{
    private static $places = [
        1000248 => 'London Victoria',
        1001555 => 'Mitcham Eastfields'
    ];

    /**
     * @param $origin
     * @param $destination
     * @param $date
     * @param $hour
     * @param $minute
     * @param $m
     * @param $matches
     */
    public static function printTrainJourneys($origin, $destination, $date)
    {
        echo self::$places[$origin] . ' - ' . self::$places[$destination] . " \n";
        echo $date->format("d-m-Y") . "\n";

        $hour = $date->format('H');
        $minute = $date->format('i');
        $date = $date->format("Ymd");
        //http://tfl.gov.uk/plan-a-journey/results?IsAsync=False&JpType=publictransport&From=Victoria+%28London%29%2c+Victoria&FromGeolocation=&ToGeolocation=&ViaGeolocation=&To=Mitcham%2c+Mitcham+Eastfields&TimeIs=departing&Date=20141209&Time=1100&Via=&NationalSearch=false&JourneyPreference=leasttime&Mode=tube&Mode=bus&Mode=dlr&Mode=river-bus&Mode=tram&Mode=cable-car&Mode=overground&Mode=national-rail&Mode=coach&AccessibilityPreference=norequirements&WalkingSpeed=average&MaxWalkingMinutes=40&CyclePreference=AllTheWay&SavePreferences=false&FromId=1000248&ToId=1001555
        $url = "http://journeyplanner.tfl.gov.uk/user/XSLT_TRIP_REQUEST2?language=en&execInst=&sessionID=0&ptOptionsActive=1&itOptionsActive=1&imparedOptionsActive=1&ptAdvancedOptions=1&place_origin=London&place_destination=London&show_origin=Victoria&show_destination=mitcham+eastfields&type_origin=stop&type_destination=stop&itdTripDateTimeDepArr=dep&datepicker=Today&stepfree-access=no-requirements&routeType=LEASTTIME&includedMeans=checkbox&inclMOT_2=on&inclMOT_1=on&inclMOT_5=on&inclMOT_0=on&inclMOT_4=on&inclMOT_9=on&inclMOT_7=on&inclMOT_8=on&inclMOT_3=on&trITMOTvalue101=60&trITMOTvalue=20&trITMOT=100&changeSpeed=normal&name_origin=$origin&name_destination=$destination&itdDate=$date&itdTimeHour=$hour&itdTimeMinute=$minute";
        $data = file_get_contents($url);

        preg_match_all("/tr class=\"det_hov.*?\/tr\>/", $data, $m);
        if (is_array($m)) {
            // var_dump($m);die;
            foreach ($m[0] as $row) {
                $regexp = "<td\s[^>]*>(.*)<\/td>";

                if (preg_match_all("/$regexp/siU", $row, $matches, PREG_SET_ORDER)) {
                    echo $matches[1][1] . ' - ' . $matches[2][1] . "\n";
                }
            }
        }
    }

    public static function printTubeStauts()
    {
        $data = file_get_contents('http://www.tfl.gov.uk/tube-dlr-overground/status/');
        $data = str_replace("\n", '', $data);
        $data = str_replace("\r", '', $data);
//        var_dump($data);die;
        preg_match_all("/rainbow-list-item victoria.*?\/li\>/im", $data, $matches);
        if (!$matches[0][0]) {
            throw new \Exception("Probably regexp needs changing here");
        }

        preg_match_all("/disruption-summary.*?\/span\>/", $matches[0][0], $m);
        if (!$m[0][0]) {
            throw new \Exception("Probably regexp needs changing here");
        }
        preg_match("/span\>(.*)\<\/span\>/", $m[0][0], $x);
        if (!$x[0][1]) {
            throw new \Exception("Probably regexp needs changing here");
        }
        echo 'Victoria Line: ' . str_replace(['  ', '<br />'], '', $x[1]) . "\n";
    }
}
Tfl::printTubeStauts();
echo "\n";
$destination = 1000248;
$origin = 1001555;

$date = "20131101";//Ymd
$date = new DateTime();

//http://tfl.gov.uk/plan-a-journey/results?IsAsync=true&JpType=publictransport&From=Mitcham+Eastfields&FromGeolocation=&ToGeolocation=&ViaGeolocation=&To=Victoria&TimeIs=departing&Date=20141130&Time=2200&Via=&NationalSearch=false&JourneyPreference=leasttime&Mode=tube&Mode=bus&Mode=dlr&Mode=river-bus&Mode=tram&Mode=cable-car&Mode=overground&Mode=national-rail&Mode=coach&AccessibilityPreference=norequirements&WalkingSpeed=average&MaxWalkingMinutes=40&CyclePreference=AllTheWay&SavePreferences=false&FromId=1001555&ToId=1000248

Tfl::printTrainJourneys($origin, $destination, $date);

echo "\n";

$origin = 1000248;
$destination = 1001555;
Tfl::printTrainJourneys($origin, $destination, $date);
