<?php

require_once __DIR__ . '/../core.php';

$url = filter_input(INPUT_POST, 'url');

$link = getAffiliateLink($url);

renderTemplate('cpanel_links_generate', compact([
    'url',
    'link',
    ]));
