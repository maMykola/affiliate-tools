<?php

require_once __DIR__ . '/../../include/constants.php';
require_once VENDOR_DIR . '/autoload.php';
require_once LIB_DIR . '/templates.php';

# configure twig
$loader = new Twig_Loader_Filesystem(TWIG_TEMPLATES_DIR);
$twig = new Twig_Environment($loader, array(
    'cache' => TWIG_CACHE_DIR,
));
