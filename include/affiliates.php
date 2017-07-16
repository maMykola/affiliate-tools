<?php

/**
 * Return information for affiliate url
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function getAffiliateLink($url)
{
    $link = loadAffiliateLink($url);
    return !empty($link) ? $link : addAffiliateLink($url);
}

/**
 * Load information for affilaite url
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function loadAffiliateLink($url)
{
    $dbh = getConnection();

    $sql = "SELECT * FROM links WHERE url = :url LIMIT 1";

    $sth = $dbh->prepare($sql);
    $sth->execute(['url' => $url]);

    $data = $sth->fetch(PDO::FETCH_ASSOC);

    return empty($data) ? null : genearteAffiliateLinks($data);
}

/**
 * Add information about affiliate url
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function addAffiliateLink($url)
{
    $dbh = getconnection();

    $data = ['url' => $url];

    $sql = "INSERT INTO links (url) VALUES (:url)";
    $sth = $dbh->prepare($sql);
    $sth->execute($data);

    $link_id = $dbh->lastInsertId();
    if (empty($link_id)) {
        return null;
    }

    $data['id'] = intval($link_id);

    return genearteAffiliateLinks($data);
}

/**
 * Return link information with generated links
 *
 * @param  array  $data
 * @return array
 * @author Mykola Martynov
 **/
function genearteAffiliateLinks($data)
{
    $links = [
        'affiliate' => buildAffiliateLink($data),
        'local' => buildLocalLink($data),
    ];

    return array_merge($data, $links);
}

/**
 * Genearte affiliate link for other sites
 *
 * @param  string  $info
 * @return string
 * @author Mykola Martynov
 **/
function buildAffiliateLink($info)
{
    $config = getParameters('affiliate');
    if (empty($config)) {
        return null;
    }

    return "http://{$config['domain']}/track?cmp={$config['campaign']}&pid={$info['id']}";
}

/**
 * undocumented function
 *
 * @return void
 * @author Mykola Martynov
 **/
function buildLocalLink($info)
{
    $config = getParameters('affiliate');
    if (empty($config)) {
        return null;
    }

    return "{$config['go_link']}?pid={$info['id']}";
}
