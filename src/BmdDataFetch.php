<?php

namespace Saidy\BmdStationDataFetch;

use GuzzleHttp\Client;
use Saidy\BmdStationDataFetch\Helper;
use Saidy\BmdStationDataFetch\Decoder;

class BmdDataFetch
{
    public static function getStationList()
    {
        $token = Helper::getToken();
        $url = "weather-condition/public.php?public=station&key={$token}";
        $response = self::GET($url, true);
        return $response["data"];
    }

    public static function getStationData($stationCode, $rows = false)
    {
        $token = Helper::getToken();
        $url = "weather-condition/public.php?public=lastdata&key={$token}&stCode=$stationCode";
        if ($rows) {
            $url .= "&rows=true";
        }
        $response = self::GET($url, true);
        $data = $response["data"];
        if (isset($data["row"])) {
            foreach ($data["row"] as $key => $item) {
                $item["date_time"] = Helper::changeDateTimeZone($item["date_time"], "Y-m-d H:i:s");
                $item["timeUpdate"] = Helper::changeDateTimeZone($item["timeUpdate"], "Y-m-d H:i:s");
                $decoded = Decoder::DecodeBMD($item["rbody"]);
                unset($item["rbody"]);
                $data["row"][$key] = array_merge($item, $decoded);
            }
        }
        return isset($data["row"]) ? $data["row"] : $data;
    }

    private static function GET($url, $json = false, $return_response = false)
    {
        $client = new Client([
            'base_uri' => 'https://live3.bmd.gov.bd/',
            // 'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', $url);
        $body = $response->getBody();
        $stringBody = (string) $body;
        if ($json) {
            $stringBody = json_decode($stringBody, true);
        }
        return $return_response ? $response : $stringBody;
    }
}
