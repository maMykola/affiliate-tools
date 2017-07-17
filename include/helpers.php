<?php

/**
 * Return true if given $url is valid local or external link
 *
 * @param  string  $url
 * @return boolean
 * @author Mykola Martynov
 **/
function isValidUrl($url)
{
    $pattern = '[-a-z0-9+&@#\/%?=~_|!:,.;\'*]*[-a-z0-9+&@#\/%=~_|.\'*]';

    return preg_match("/^\/{$pattern}$/i", $url) || preg_match("/^\b(?:(?:https?|ftp):\/\/|www\.){$pattern}$/i", $url);
}
