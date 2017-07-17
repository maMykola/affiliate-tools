<?php

require_once __DIR__ . '/../core.php';

$access_key = filter_input(INPUT_POST, 'access_key');
if (!apiAccessKeyValid($access_key)) {
    exit;
}

$link_id = intval(filter_input(INPUT_POST, 'target_id', FILTER_VALIDATE_INT));

$info = getAffiliateInfo($link_id);
if (empty($info)) {
    exit;
}

echo json_encode($info);
