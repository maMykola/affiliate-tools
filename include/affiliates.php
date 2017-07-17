<?php

/**
 * Return information about affiliate link by identifier
 *
 * @param  integer  $link_id
 * @return array
 * @author Mykola Martynov
 **/
function getAffiliateInfo($link_id)
{
    $dbh = getConnection();

    $sql = "SELECT * FROM links WHERE id = :id LIMIT 1";

    $sth = $dbh->prepare($sql);
    $sth->execute(['id' => $link_id]);

    $data = $sth->fetch(PDO::FETCH_ASSOC);

    return empty($data) ? null : generateAffiliateLinks($data);
}

/**
 * Return information for affiliate url
 *
 * @param  string  $url
 * @param  string  $target
 * @return array
 * @author Mykola Martynov
 **/
function getAffiliateLink($url, $target)
{
    $link = loadAffiliateLink($url, $target);
    if (empty($link)) {
        $link = addAffiliateLink($url, $target);
    }

    return empty($link) ? null : generateAffiliateLinks($link);
}

/**
 * Load information for affilaite url
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function loadAffiliateLink($url, $target)
{
    $dbh = getConnection();

    $sql = <<< SQL_QUERY
SELECT
    ln.id id,
    ln.target_id target_id,
    ln.url url,
    tr.url target
FROM links ln
    LEFT JOIN targets tr on tr.id = ln.target_id
WHERE ln.url = :url and ln.target_id = :target_id
LIMIT 1
SQL_QUERY;

    $sth = $dbh->prepare($sql);
    $sth->execute(['url' => $url, 'target_id' => getTargetLinkId($target)]);

    $data = $sth->fetch(PDO::FETCH_ASSOC);

    return $data;
}

/**
 * Add information about affiliate url
 *
 * @param  string  $url
 * @param  string  $target
 * @return array
 * @author Mykola Martynov
 **/
function addAffiliateLink($url, $target)
{
    $dbh = getconnection();

    $target_id = getTargetLinkId($target);
    $data = ['url' => $url, 'target_id' => $target_id];

    $sql = "INSERT INTO links (url, target_id) VALUES (:url, :target_id)";
    $sth = $dbh->prepare($sql);
    $sth->execute($data);

    $link_id = $dbh->lastInsertId();
    if (empty($link_id)) {
        return null;
    }

    $link = [
        'id' => intval($link_id),
        'target_id' => $target_id,
        'url' => $url,
        'target' => $target,
    ];

    return $link;
}

/**
 * Return identifier for given url as a target
 *
 * @param  string  $url
 * @return integer
 * @author Mykola Martynov
 **/
function getTargetLinkId($url)
{
    $info = loadTargetInfo($url);
    if (empty($info)) {
        $info = addTargetInfo($url);
    }

    return empty($info['id']) ? null : $info['id'];
}

/**
 * Return information about target link
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function loadTargetInfo($url)
{
    $dbh = getConnection();

    $sql = "SELECT * FROM targets WHERE url = :url";
    $sth = $dbh->prepare($sql);
    $sth->execute(['url' => $url]);

    $data = $sth->fetch(PDO::FETCH_ASSOC);

    return empty($data) ? null : $data;
}

/**
 * Add information about target link
 *
 * @param  string  $url
 * @return array
 * @author Mykola Martynov
 **/
function addTargetInfo($url)
{
    $dbh = getConnection();

    $sql = "INSERT INTO targets (url) VALUES (:url)";
    $sth = $dbh->prepare($sql);
    $sth->execute(['url' => $url]);

    $link_id = $dbh->lastInsertId();
    if (empty($link_id)) {
        return null;
    }

    $info = [
        'id' => $link_id,
        'url' => $url,
    ];

    return $info;
}

/**
 * Return link information with generated links
 *
 * @param  array  $data
 * @return array
 * @author Mykola Martynov
 **/
function generateAffiliateLinks($data)
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
