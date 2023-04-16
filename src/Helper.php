<?php

namespace Saidy\BmdStationDataFetch;

class Helper
{
    public static function getToken()
    {
        $date_utc = new \DateTime("now", new \DateTimeZone("UTC"));
        $mtime = $date_utc->format("m-d");
        return "helloJoy{$mtime}";

        // $mtime = gmdate("Y-m-d\TH:i:s\Z");
        // $mtime = gmdate("m-d");
        // return "helloJoy{$mtime}";
    }
}
