<?php
/**
 * Plugin Name: Greendiz Blog
 * Description: Импорт статей блога, структура рубрик и меток, содержание статьи и тултипы.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: greendiz-blog
 */

if (!defined('ABSPATH')) {
    exit;
}

final class GD_Blog
{
    const SLUG = 'gd-blog';
    const META_ALT = '_gd_cover_alt';
    const META_TITLE = '_gd_cover_title';
    const META_NUM = '_gd_plan_number';

    private static $status_cache = [];

    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_post_gd_blog_structure', [__CLASS__, 'structure']);
        add_action('admin_post_gd_blog_import', [__CLASS__, 'import']);
        add_action('added_post_meta', [__CLASS__, 'thumbnail_changed'], 10, 4);
        add_action('updated_post_meta', [__CLASS__, 'thumbnail_changed'], 10, 4);
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'image_attributes'], 10, 2);
        add_filter('the_content', [__CLASS__, 'content'], 12);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
    }

    public static function menu()
    {
        add_management_page('Импорт статей блога', 'Блог: импорт', 'manage_options', self::SLUG, [__CLASS__, 'page']);
    }

    private static function data()
    {
        $file = plugin_dir_path(__FILE__) . 'data/structure.json';
        if (!is_readable($file)) {
            return null;
        }
        return json_decode(file_get_contents($file), true);
    }

    private static function find_post($slug)
    {
        $posts = get_posts([
            'name' => $slug,
            'post_type' => 'post',
            'post_status' => ['draft', 'pending', 'publish', 'future', 'private'],
            'numberposts' => 1,
            'suppress_filters' => true,
        ]);
        return $posts ? $posts[0] : null;
    }

    private static function redirect($args)
    {
        wp_safe_redirect(add_query_arg($args, admin_url('tools.php?page=' . self::SLUG)));
        exit;
    }

    private static function check($action)
    {
        if (!current_user_can('manage_options')) {
            wp_die('Недостаточно прав.');
        }
        check_admin_referer($action);
    }

    private static function upsert_term($item, $taxonomy)
    {
        $existing = get_term_by('slug', $item['slug'], $taxonomy);
        if ($existing) {
            $term_id = (int) $existing->term_id;
            $created = false;
        } else {
            $result = wp_insert_term($item['name'], $taxonomy, [
                'slug' => $item['slug'],
                'description' => $item['description'],
            ]);
            if (is_wp_error($result)) {
                return [0, false];
            }
            $term_id = (int) $result['term_id'];
            $created = true;
        }
        if ($created || get_term_meta($term_id, 'rank_math_title', true) === '') {
            update_term_meta($term_id, 'rank_math_title', wp_slash($item['seo_title']));
        }
        if ($created || get_term_meta($term_id, 'rank_math_description', true) === '') {
            update_term_meta($term_id, 'rank_math_description', wp_slash($item['seo_description']));
        }
        return [$term_id, $created];
    }

    public static function structure()
    {
        self::check('gd_blog_structure');
        $data = self::data();
        if (!$data) {
            self::redirect(['gd_error' => rawurlencode('Не найден файл data/structure.json.')]);
        }

        $cats = [];
        $tags = [];
        $count = ['cats' => 0, 'tags' => 0, 'posts' => 0];

        foreach ($data['rubrics'] as $item) {
            list($id, $created) = self::upsert_term($item, 'category');
            if ($id) {
                $cats[$item['slug']] = $id;
                $count['cats'] += $created ? 1 : 0;
            }
        }

        foreach ($data['tags'] as $item) {
            list($id, $created) = self::upsert_term($item, 'post_tag');
            if ($id) {
                $tags[$item['slug']] = $id;
                $count['tags'] += $created ? 1 : 0;
            }
        }

        foreach ($data['posts'] as $item) {
            if (self::find_post($item['slug'])) {
                continue;
            }
            $tag_ids = [];
            foreach ($item['tags'] as $slug) {
                if (isset($tags[$slug])) {
                    $tag_ids[] = $tags[$slug];
                }
            }
            $post_id = wp_insert_post(wp_slash([
                'post_type' => 'post',
                'post_status' => 'draft',
                'post_title' => $item['title'],
                'post_name' => $item['slug'],
                'post_content' => '',
                'post_category' => isset($cats[$item['category']]) ? [$cats[$item['category']]] : [],
                'meta_input' => [
                    self::META_NUM => (int) $item['n'],
                    'rank_math_focus_keyword' => $item['focus_keyword'],
                ],
            ]), true);
            if (is_wp_error($post_id)) {
                continue;
            }
            wp_set_post_terms($post_id, $tag_ids, 'post_tag');
            $count['posts']++;
        }

        self::redirect([
            'gd_done' => 'structure',
            'gd_cats' => $count['cats'],
            'gd_tags' => $count['tags'],
            'gd_posts' => $count['posts'],
        ]);
    }

    private static function parse($raw)
    {
        $raw = str_replace(["\r\n", "\r"], "\n", $raw);
        if (!preg_match('/===ИМПОРТ===\s*\n(.*?)\n===ТЕКСТ===\s*\n(.*?)\n===КОНЕЦ===/su', $raw, $m)) {
            return new WP_Error('format', 'Не найдены маркеры ===ИМПОРТ===, ===ТЕКСТ=== и ===КОНЕЦ===.');
        }
        $fields = [];
        foreach (explode("\n", $m[1]) as $line) {
            if (preg_match('/^\s*([a-z_]+)\s*:\s*(.*)$/u', $line, $f)) {
                $fields[$f[1]] = trim($f[2]);
            }
        }
        foreach (['slug', 'h1'] as $key) {
            if (empty($fields[$key])) {
                return new WP_Error('field', 'Не заполнено поле ' . $key . '.');
            }
        }
        $fields['body'] = trim($m[2]);
        if ($fields['body'] === '') {
            return new WP_Error('body', 'Пустой текст статьи.');
        }
        return $fields;
    }

    public static function import()
    {
        self::check('gd_blog_import');
        $raw = isset($_POST['gd_payload']) ? wp_unslash($_POST['gd_payload']) : '';
        $fields = self::parse($raw);
        if (is_wp_error($fields)) {
            self::redirect(['gd_error' => rawurlencode($fields->get_error_message())]);
        }

        $post = self::find_post(sanitize_title($fields['slug']));
        if (!$post) {
            self::redirect(['gd_error' => rawurlencode('Черновик со slug «' . $fields['slug'] . '» не найден.')]);
        }

        $update = [
            'ID' => $post->ID,
            'post_title' => $fields['h1'],
            'post_content' => $fields['body'],
        ];
        if (isset($fields['excerpt'])) {
            $update['post_excerpt'] = $fields['excerpt'];
        }
        $result = wp_update_post(wp_slash($update), true);
        if (is_wp_error($result)) {
            self::redirect(['gd_error' => rawurlencode($result->get_error_message())]);
        }

        $meta = [
            'seo_title' => 'rank_math_title',
            'seo_description' => 'rank_math_description',
            'focus_keyword' => 'rank_math_focus_keyword',
            'cover_alt' => self::META_ALT,
            'cover_title' => self::META_TITLE,
        ];
        foreach ($meta as $field => $key) {
            if (isset($fields[$field]) && $fields[$field] !== '') {
                update_post_meta($post->ID, $key, wp_slash($fields[$field]));
            }
        }

        $thumb = (int) get_post_thumbnail_id($post->ID);
        if ($thumb) {
            self::apply_cover($post->ID, $thumb);
        }

        self::redirect(['gd_done' => 'import', 'gd_post' => $post->ID]);
    }

    public static function thumbnail_changed($meta_id, $post_id, $meta_key, $meta_value)
    {
        if ($meta_key === '_thumbnail_id' && get_post_type($post_id) === 'post') {
            self::apply_cover((int) $post_id, (int) $meta_value);
        }
    }

    private static function apply_cover($post_id, $attachment_id)
    {
        if (!$attachment_id || get_post_type($attachment_id) !== 'attachment') {
            return;
        }
        $alt = get_post_meta($post_id, self::META_ALT, true);
        $title = get_post_meta($post_id, self::META_TITLE, true);
        if ($alt !== '') {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', wp_slash($alt));
        }
        if ($title !== '') {
            wp_update_post(wp_slash(['ID' => $attachment_id, 'post_title' => $title]));
        }
    }

    public static function image_attributes($attr, $attachment)
    {
        if (empty($attr['title']) && is_singular('post') && (int) get_post_thumbnail_id(get_queried_object_id()) === (int) $attachment->ID) {
            $attr['title'] = get_the_title($attachment->ID);
        }
        return $attr;
    }

    private static function post_status_by_url($url)
    {
        $home = wp_parse_url(home_url());
        $parts = wp_parse_url($url);
        if (empty($parts['path']) || (!empty($parts['host']) && $parts['host'] !== $home['host'])) {
            return null;
        }
        if (strpos($parts['path'], '/blog/') === false) {
            return null;
        }
        $slug = basename(untrailingslashit($parts['path']));
        if (!array_key_exists($slug, self::$status_cache)) {
            $post = self::find_post($slug);
            self::$status_cache[$slug] = $post ? $post->post_status : null;
        }
        return self::$status_cache[$slug];
    }

    private static function unwrap_unpublished($html)
    {
        return preg_replace_callback('/<a\s[^>]*href=(["\'])(.*?)\1[^>]*>(.*?)<\/a>/su', function ($m) {
            $status = self::post_status_by_url(html_entity_decode($m[2]));
            return ($status && $status !== 'publish') ? $m[3] : $m[0];
        }, $html);
    }

    private static function heading_id($text, $index, &$used)
    {
        $id = sanitize_title($text);
        if ($id === '' || strpos($id, '%') !== false) {
            $id = 'razdel-' . $index;
        }
        $base = $id;
        $i = 2;
        while (isset($used[$id])) {
            $id = $base . '-' . $i++;
        }
        $used[$id] = true;
        return $id;
    }

    public static function content($html)
    {
        if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
            return $html;
        }

        $html = self::unwrap_unpublished($html);
        $html = preg_replace('/<table\b.*?<\/table>/su', '<div class="article-table">$0</div>', $html);

        $items = [];
        $used = [];
        $index = 0;
        $html = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/su', function ($m) use (&$items, &$used, &$index) {
            $index++;
            $attrs = $m[1];
            $text = trim(wp_strip_all_tags($m[2]));
            if (preg_match('/\sid=(["\'])(.*?)\1/u', $attrs, $idm)) {
                $id = $idm[2];
                $used[$id] = true;
            } else {
                $id = self::heading_id($text, $index, $used);
                $attrs .= ' id="' . esc_attr($id) . '"';
            }
            if (preg_match('/\sclass=(["\'])(.*?)\1/u', $attrs)) {
                $attrs = preg_replace('/\sclass=(["\'])(.*?)\1/u', ' class="$2 article-h"', $attrs, 1);
            } else {
                $attrs .= ' class="article-h"';
            }
            $items[] = ['id' => $id, 'text' => $text];
            return '<h2' . $attrs . '>' . $m[2] . '</h2>';
        }, $html);

        if (count($items) < 3) {
            return $html;
        }

        $nav = '<nav class="article-toc" aria-label="Содержание статьи"><p class="article-toc__title">Содержание</p><ol>';
        foreach ($items as $item) {
            $nav .= '<li><a href="#' . esc_attr($item['id']) . '">' . esc_html($item['text']) . '</a></li>';
        }
        $nav .= '</ol></nav>';

        foreach (['article-disclaimer', 'article-lead'] as $class) {
            $pattern = '/<p[^>]*class=(["\'])[^"\']*\b' . $class . '\b[^"\']*\1[^>]*>.*?<\/p>/su';
            if (preg_match($pattern, $html, $m, PREG_OFFSET_CAPTURE)) {
                $pos = $m[0][1] + strlen($m[0][0]);
                return substr($html, 0, $pos) . $nav . substr($html, $pos);
            }
        }
        return $nav . $html;
    }

    public static function assets()
    {
        if (!is_singular('post')) {
            return;
        }
        $url = plugin_dir_url(__FILE__) . 'assets/';
        $dir = plugin_dir_path(__FILE__) . 'assets/';
        wp_enqueue_style('gd-blog', $url . 'blog.css', [], filemtime($dir . 'blog.css'));
        wp_enqueue_script('gd-blog', $url . 'blog.js', [], filemtime($dir . 'blog.js'), ['strategy' => 'defer', 'in_footer' => true]);
    }

    private static function notice()
    {
        if (!empty($_GET['gd_error'])) {
            printf('<div class="notice notice-error"><p>%s</p></div>', esc_html(rawurldecode(wp_unslash($_GET['gd_error']))));
            return;
        }
        $done = isset($_GET['gd_done']) ? sanitize_key($_GET['gd_done']) : '';
        if ($done === 'structure') {
            printf(
                '<div class="notice notice-success"><p>Создано рубрик: %d, меток: %d, черновиков: %d. Уже существующие пропущены.</p></div>',
                (int) ($_GET['gd_cats'] ?? 0),
                (int) ($_GET['gd_tags'] ?? 0),
                (int) ($_GET['gd_posts'] ?? 0)
            );
        }
        if ($done === 'import' && !empty($_GET['gd_post'])) {
            $id = (int) $_GET['gd_post'];
            printf(
                '<div class="notice notice-success"><p>Статья «%s» обновлена. <a href="%s">Редактировать</a> · <a href="%s" target="_blank" rel="noopener">Просмотр</a></p></div>',
                esc_html(get_the_title($id)),
                esc_url(get_edit_post_link($id)),
                esc_url(get_preview_post_link($id))
            );
        }
    }

    public static function page()
    {
        $data = self::data();
        $total = $data ? count($data['posts']) : 0;
        $existing = 0;
        if ($data) {
            foreach ($data['posts'] as $item) {
                $existing += self::find_post($item['slug']) ? 1 : 0;
            }
        }
        ?>
        <div class="wrap">
            <h1>Импорт статей блога</h1>
            <?php self::notice(); ?>

            <h2>1. Структура блога</h2>
            <p>Создаёт рубрики и метки с описаниями и SEO-полями Rank Math, а также черновики всех статей плана с заголовком, slug, рубрикой и метками. Уже существующие рубрики, метки и записи не меняются.</p>
            <p>Черновиков из плана на сайте: <strong><?php echo (int) $existing; ?> из <?php echo (int) $total; ?></strong>.</p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('gd_blog_structure'); ?>
                <input type="hidden" name="action" value="gd_blog_structure">
                <?php submit_button('Создать структуру', 'secondary', 'submit', false); ?>
            </form>

            <h2 style="margin-top:2em">2. Импорт статьи</h2>
            <p>Вставьте ответ целиком или только блок от <code>===ИМПОРТ===</code> до <code>===КОНЕЦ===</code>. Остальной текст игнорируется. Статья останется черновиком.</p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('gd_blog_import'); ?>
                <input type="hidden" name="action" value="gd_blog_import">
                <textarea name="gd_payload" rows="18" style="width:100%;font-family:monospace" required></textarea>
                <?php submit_button('Импортировать'); ?>
            </form>
        </div>
        <?php
    }
}

GD_Blog::init();
