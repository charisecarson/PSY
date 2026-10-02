<?php
if (!defined('ABSPATH')) {
    exit;
}

define('GDPSY_POST_STRUCTURE', '/blog/%postname%/');

add_filter('register_post_type_args', function ($args, $post_type) {
    if ($post_type === 'portfolio') {
        $rewrite = isset($args['rewrite']) && is_array($args['rewrite']) ? $args['rewrite'] : array();
        $rewrite['slug'] = 'portfolio';
        $rewrite['with_front'] = false;
        $args['rewrite'] = $rewrite;
        $args['has_archive'] = true;
    }
    return $args;
}, 20, 2);

add_action('after_switch_theme', function () {
    global $wp_rewrite;
    if ($wp_rewrite->permalink_structure !== GDPSY_POST_STRUCTURE) {
        $wp_rewrite->set_permalink_structure(GDPSY_POST_STRUCTURE);
    }
    flush_rewrite_rules();
});

add_action('init', function () {
    $page_id = (int) get_option('page_for_posts');
    if (!$page_id) {
        return;
    }
    $uri = get_page_uri($page_id);
    if (!$uri) {
        return;
    }
    add_rewrite_rule(
        '^' . preg_quote($uri, '#') . '/page/([0-9]{1,})/?$',
        'index.php?pagename=' . $uri . '&paged=$matches[1]',
        'top'
    );
}, 20);
