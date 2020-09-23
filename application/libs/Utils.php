<?php

namespace application\libs;

class Utils
{
    static public function fmtTimeToInt($str)
    {
        $matches = [];
        preg_match_all('/(\d+)m/', $str, $matches);
        $arr_minutes = $matches[1];
        
        unset($matches);
        
        preg_match_all('/(\d+)h/', $str, $matches);
        $arr_hours = $matches[1];
        
        $seconds = 0;
        
        foreach ($arr_minutes as $minutes)
        {
            $seconds += $minutes * 60;
        }
        
        foreach ($arr_hours as $hours)
        {
            $seconds += $hours * 3600;
        }
        
        return $seconds;
    }
}