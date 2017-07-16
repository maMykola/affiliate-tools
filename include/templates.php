<?php

/**
 * Render twig template
 *
 * @param  string  $name
 * @param  array   $params
 * @param  string  $type
 * @return void
 * @author Mykola Martynov
 **/
function renderTemplate($name, $params = [], $type = 'html')
{
    global $twig;

    $template_name = str_replace('_', '/', $name) . ".$type.twig";

    if (!file_exists(TWIG_TEMPLATES_DIR . $template_name)) {
        return;
    }

    echo $twig->render($template_name, $params);
}
