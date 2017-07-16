<?php

/**
 * Return parameters from config file by name.
 * Return default value if nothing found.
 *
 * @param  string  $name
 * @return mixed
 * @author Mykola Martynov
 **/
function getParameters($name, $default = null)
{
    static $parameters = [];

    if (empty($parameters)) {
        $parameters = loadParameters();
    }

    return empty($parameters[$name]) ? $default : $parameters[$name];
}

/**
 * Load parameters from config
 *
 * @param  array $default
 * @return array
 * @author Mykola Martynov
 **/
function loadParameters($default = [])
{
    $filename = CONFIG_DIR . 'parameters.php';
    if (!file_exists($filename) || !is_readable($filename)) {
        return $default;
    }

    return include($filename);
}
