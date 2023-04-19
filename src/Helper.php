<?php

namespace Saidy\BmdStationDataFetch;

class Helper
{
    public static function getToken()
    {
        $date_utc = new \DateTime("now", new \DateTimeZone("UTC"));
        $mtime = $date_utc->format("m-d");
        return "helloJoy{$mtime}";
    }

    public static function changeDateTimeZone($datetime, $targetFormat)
    {
        $date_utc = new \DateTime($datetime, new \DateTimeZone("UTC"));
        $date_utc->setTimezone(new \DateTimeZone("Asia/Dhaka"));
        return $date_utc->format($targetFormat);
    }
}
