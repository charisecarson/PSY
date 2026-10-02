<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('widgets_init', function () {
    register_sidebar(array(
        'name' => 'Колонка блога',
        'id' => 'blog-sidebar',
        'description' => 'Правая колонка на главной блога, в архивах и записях.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget__title">',
        'after_title' => '</h2>',
    ));
});

add_action('widgets_init', function () {
    $standard = array(
        'WP_Widget_Pages',
        'WP_Widget_Calendar',
        'WP_Widget_Archives',
        'WP_Widget_Links',
        'WP_Widget_Media_Audio',
        'WP_Widget_Media_Image',
        'WP_Widget_Media_Gallery',
        'WP_Widget_Media_Video',
        'WP_Widget_Meta',
        'WP_Widget_Search',
        'WP_Widget_Text',
        'WP_Widget_Categories',
        'WP_Widget_Recent_Posts',
        'WP_Widget_Recent_Comments',
        'WP_Widget_RSS',
        'WP_Widget_Tag_Cloud',
        'WP_Nav_Menu_Widget',
        'WP_Widget_Custom_HTML',
        'WP_Widget_Block',
    );
    foreach ($standard as $class) {
        unregister_widget($class);
    }
}, 20);

add_filter('acf/fields/relationship/query/name=projects', function ($args) {
    $args['meta_query'] = array(array('key' => 'case-type', 'value' => 'project'));
    return $args;
});

add_filter('acf/fields/relationship/query/name=concepts', function ($args) {
    $args['meta_query'] = array(array('key' => 'case-type', 'value' => 'consept'));
    return $args;
});

add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_post_type_archive('portfolio')) {
        $query->set('posts_per_page', -1);
        $query->set('no_found_rows', true);
    }
});
