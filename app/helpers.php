<?php

function prepurl($url)
{
    $url = str_replace(['+', ' '], '-', strtolower(strval($url)));
    $url = preg_replace(['/[^0-9a-z\-]/', '/-+/'], ['', '-'], $url);
    return trim($url, '-');
}

function makeurl($eventname, $eventid)
{
    return prepurl($eventname) . '-' . $eventid;
}

function getEntryStatusText($entrystatusid)
{
    switch ($entrystatusid) {
        case 1:
            return 'warning';
        case 2:
            return 'succes';
        default:
            return 'danger';
    }
}

if (!function_exists('mb_strcut')) {
    function mb_strcut($string, $start, $length = null, $encoding = null)
    {
        $encoding = $encoding ?: 'UTF-8';
        $strlen = mb_strlen($string, $encoding);
        if ($start < 0) $start = max(0, $strlen + $start);
        if ($length === null) $length = $strlen - $start;
        return mb_substr($string, $start, $length, $encoding);
    }
}