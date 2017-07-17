<?php

/**
 * Return true if given private key is exists.
 *
 * @param  string  $private_key
 * @return boolean
 * @author Mykola Martynov
 **/
function apiAccessKeyValid($private_key)
{
    $access_keys = getParameters('api_keys');
    
    return !empty($access_keys[$private_key]);
}
