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

function debug(...$data)
{
    if (Auth::id() != 1) {
        return;
    }
    dump($data);
}

function debugx(...$data)
{
    if (Auth::id() != 1) {
        return;
    }
    dd($data);
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
