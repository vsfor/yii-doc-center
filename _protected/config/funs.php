<?php

if (!function_exists('http_parse_params')) {
    /**
     * 解析 http_build_query 生成的参数串
     * @param $str
     * @return array
     */
    function http_parse_params($str)
    {
        $paramL = explode('&', $str);
        $final = [];
        foreach ($paramL as $paramKV) {
            if (!strpos($paramKV, '=')) {
                $final[$paramKV] = null;
                continue;
            }
            list($key, $val) = explode('=', $paramKV);
            $tk = urldecode($key);
            $tv = urldecode($val);
            $tk = str_replace(']', '', $tk);
            $pka = explode('[', $tk);
            $v = count($pka);
            if ($v == 1) {
                $final[$tk] = $tv;
            } else if ($v == 2) {
                $final[$pka[0]][$pka[1]] = $tv;
            } else if ($v == 3) {
                $final[$pka[0]][$pka[1]][$pka[2]] = $tv;
            } else {
                $final[$tk] = $tv;
            }
        }
        return $final;
    }

}