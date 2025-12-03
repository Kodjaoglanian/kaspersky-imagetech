<?php
/**
 * Template da landing page principal
 */

get_header();

$rendered = false;

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $content = trim(get_the_content());
        if ($content !== '') {
            the_content();
        } else {
            echo kaspersky_imagetech_render_default_pattern();
        }
        $rendered = true;
    }
}

if (!$rendered) {
    echo kaspersky_imagetech_render_default_pattern();
}

get_footer();
