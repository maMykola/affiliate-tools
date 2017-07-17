<?php

require_once __DIR__ . '/../core.php';

$url = trim(filter_input(INPUT_POST, 'url'));
$target = trim(filter_input(INPUT_POST, 'target'));
$errors = [];

if (empty($url)) {
    $errors['url'] = 'Required field';
} elseif (!isValidUrl($url)) {
    $errors['url'] = 'Malformed url';
}

if (empty($target)) {
    $errors['target'] = 'Required field';
} elseif (!isValidUrl($target)) {
    $errors['target'] = 'Malformed url';
}

if (empty($errors)) {
    $link = getAffiliateLink($url, $target);
}

renderTemplate('cpanel_links_generate', compact([
    'url',
    'target',
    'link',
    'errors',
    ]));
