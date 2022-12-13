<?php
add_theme_support("custom-background");
add_theme_support('custom-logo');

function my_script(){
    wp_enqueue_style('style', get_theme_file_uri()."/style.css");
    wp_enqueue_script('script', get_theme_file_uri()."/script.js", ["jquery"], "1.0.0", true);
}

add_action("wp_enqueue_scripts", "my_script", 999);
