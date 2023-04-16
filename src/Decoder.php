<?php

namespace Saidy\BmdStationDataFetch;

class Decoder
{
    private static function getTempMax($e)
    {

        $r = "";
        $s = implode(" ", $e);

        if (strpos($s, " 333 ") > 0 && (count($e = explode(" ", explode(" 333 ", $s)[1])))) {
            for ($i = 0; $i < count($e); $i++) {
                $t = $e[$i];
                if ("10" ==  substr($t, 0, 2)) {
                    $r = substr($t, 2, 2) . "." . substr($t, 4, 1);
                }
            }
        }
        return strlen($r) ? floatval($r) : null;
    }

    private static function getTempMin($e)
    {

        $r = "";
        $s = implode(" ", $e);

        if (strpos($s, " 333 ") > 0 && (count($e = explode(" ", explode(" 333 ", $s)[1])))) {
            for ($i = 0; $i < count($e); $i++) {
                $t = $e[$i];
                if ("20" ==  substr($t, 0, 2)) {
                    $r = substr($t, 2, 2) . "." . substr($t, 4, 1);
                }
            }
        }
        return strlen($r) ? floatval($r) : null;
    }

    private static function getRainfall3($e)
    {
        $r = "0";
        $s = "0";
        $t = "0";
        $a = implode(" ", $e);
        if (strpos($a, " 333 ") > 0 && (count($e = explode(" ", explode(" 333 ", $a)[1]))))
            for ($i = 0; $i < count($e); $i++) {
                $n = $e[$i];
                if ("6" ==  substr($n, 0, 1)) {
                    $r = substr($n, 3, 1);
                    $s = substr($n, 1, 2);
                    $t = substr($n, 1, 3);
                }
            }
        return "99" == $s && intval($r) > 0  ? floatval(0.1 * intval($r)) : floatval("0");
    }

    private static function getRainfall6($e)
    {
        $r = "0";
        $s = "0";
        $t = "0";
        if ("6" ==  substr($e[9], 0, 1)) {
            $r = substr($e[9], 3, 1);
            $s = substr($e[9], 1, 2);
            $t = substr($e[9], 1, 3);
            if ("99" == $s) {
                $t = intval($r) > 0 ? .1 * intval($r) : "0";
            }
        }
        return floatval($t);
    }

    private static function getRainfall24($e)
    {
        $r = "0";
        $s = "0";
        $t = "0";
        $a = implode(" ", $e);

        if (strpos($a, " 333 ") > 0) {
            $n = explode(" ", explode(" 333 ", $a)[1]);
            if (count($n) > 0)
                for ($i = 0; $i < count($n); $i++) {
                    $u = $n[$i];
                    if ("7" == substr($u, 0, 1)) {
                        $r = substr($u, 4, 1);
                        $s = substr($u, 1, 3);
                        $t = substr($u, 1, 4);
                    }
                }
        }
        if ("999" == $s) {
            $t = "9" == $r ? -1 : (intval($r) > 0 ? intval($r) : "0");
            if (intval($t) > 0) {
                $t = .1 * intval($t);
            }
        }
        return floatval($t);
    }

    private static function getRainfall($e)
    {
        $r = "0";
        $s = intval(substr($e[1], 2, 2));
        $r = array_search($s, ["00", "06", "12", "18"]) !== false ? self::getRainfall6($e) : self::getRainfall3($e);
        return floatval($r);
    }

    private static function getDryBulb($e)
    {
        $r = $e[5];
        $s = substr($r, 1, 1);
        $t = intval($s);
        $a = substr($r, 2, 2) . "." . substr($r, 4, 1);
        return $t > 0 ? "-" . $a : $a;
    }

    public static function DecodeBMD($row)
    {
        $e = explode(" ", $row->rbody);

        $max_temp = self::getTempMax($e);
        $min_temp = self::getTempMin($e);
        $rainfall3 = self::getRainfall3($e);
        $rainfall6 = self::getRainfall6($e);
        $rainfall24 = self::getRainfall24($e);
        $rainfall = self::getRainfall($e);
        $dry_bulb = self::getDryBulb($e);

        $output = [
            "date_time" => $row->date_time,
            "maximum_t" => $max_temp,
            "minimum_t" => $min_temp,
            "rainfall" => $rainfall,
            "rainfall_3" => $rainfall3,
            "rainfall_6" => $rainfall6,
            "rainfall_24" => $rainfall24,
            "dry_bulb" => $dry_bulb,
        ];
        return $output;
    }
}
