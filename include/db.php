<?php

/**
 * Return PDO object for connection with given name
 *
 * @param  array  $config
 * @return \PDO
 * @author Mykola Martynov
 **/
function getConnection($config_name = 'default')
{
    static $pdo_list = [];

    $config = getDBConfig($config_name);
    if (empty($config)) {
        throw new Exception('DB Eror');
    }

    $dsn = getDB_DSN($config);
    $dsn_hash = "{$dsn};{$config['username']}";

    if (!empty($pdo_list[$dsn_hash])) {
        return $pdo_list[$dsn_hash];
    }

    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo_list[$dsn_hash] = $pdo;

    return $pdo;
}

/**
 * Return config data for database connection.
 * Return null if no config exists.
 *
 * @param  string  $config_name
 * @return array
 * @author Mykola Martynov
 **/
function getDBConfig($config_name)
{
    $db_config = getParameters('db_config');
    return empty($db_config[$config_name]) ? null : validateDBConfig($db_config[$config_name]);
}

/**
 * Return config data if it's valid, otherwise return null
 *
 * @param  array  $config
 * @return array
 * @author Mykola Martynov
 **/
function validateDBConfig($config)
{
    $keys = [
        'host',
        'database',
        'username',
        'password',
    ];

    foreach ($keys as $key_name) {
        if (!array_key_exists($key_name, $config)) {
            return null;
        }
    }

    return $config;
}

/**
 * Return database DSN for PDO
 *
 * @param  array  $config
 * @return string
 * @author Mykola Martynov
 **/
function getDB_DSN($config)
{
    return "mysql:host={$config['host']};dbname={$config['database']}";
}
