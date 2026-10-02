<?php
if (!defined('ABSPATH')) {
    exit;
}

function gdpsy_asset_version($file)
{
    $path = get_theme_file_path($file);
    return file_exists($path) ? (string) filemtime($path) : GDPSY_VERSION;
}

add_action('wp_enqueue_scripts', function () {
    $uri = get_theme_file_uri();

    wp_enqueue_style('gd-fonts', $uri . '/css/fonts.css', array(), gdpsy_asset_version('css/fonts.css'));
    wp_enqueue_style('gd-global', $uri . '/css/global.css', array('gd-fonts'), gdpsy_asset_version('css/global.css'));

    if (is_front_page()) {
        wp_enqueue_style('gd-front', $uri . '/css/front-page.css', array('gd-global'), gdpsy_asset_version('css/front-page.css'));
        $last_style = 'gd-front';
    } else {
        wp_enqueue_style('gd-inner', $uri . '/css/inner.css', array('gd-global'), gdpsy_asset_version('css/inner.css'));
        $last_style = 'gd-inner';
    }
    if (is_post_type_archive('portfolio')) {
        wp_enqueue_script('gd-portfolio', $uri . '/js/portfolio-filter.js', array(), gdpsy_asset_version('js/portfolio-filter.js'), array('in_footer' => true, 'strategy' => 'defer'));
    }
    wp_enqueue_style('gd-wp', $uri . '/css/wp.css', array($last_style), gdpsy_asset_version('css/wp.css'));

    wp_enqueue_script('gd-global', $uri . '/js/global.js', array(), gdpsy_asset_version('js/global.js'), array('in_footer' => true, 'strategy' => 'defer'));

    if (is_front_page()) {
        wp_enqueue_script('gd-front', $uri . '/js/front-page.js', array('gd-global'), gdpsy_asset_version('js/front-page.js'), array('in_footer' => true, 'strategy' => 'defer'));
    }
});
