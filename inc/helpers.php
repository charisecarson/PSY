<?php
if (!defined('ABSPATH')) {
    exit;
}

function gdpsy_field($name, $post_id = false)
{
    return function_exists('get_field') ? get_field($name, $post_id) : null;
}

function gdpsy_option($name)
{
    return gdpsy_field($name, 'option');
}

function gdpsy_img($file)
{
    return esc_url(get_theme_file_uri('img/' . $file));
}

function gdpsy_home($hash = '')
{
    if (is_front_page()) {
        return $hash;
    }
    return home_url('/') . $hash;
}

function gdpsy_price_keys()
{
    return array(
        'base' => 'lending',
        'concept' => 'concept',
        'blog' => 'blog',
        'autopost' => 'auto-publishing',
        'mail' => 'newsletter',
        'booking' => 'booking',
        'pay' => 'payments',
        'cabinet' => 'lk',
        'diary' => 'dairy',
        'kb' => 'knowledgebase',
        'tests' => 'tests',
        'kb_paid' => 'pay-knowledgebase',
        'courses' => 'courses',
        'pwa' => 'pwa',
    );
}

function gdpsy_price($id)
{
    static $prices = null;
    if ($prices === null) {
        $prices = array();
        $saved = gdpsy_option('price');
        $saved = is_array($saved) ? $saved : array();
        foreach (gdpsy_price_keys() as $key => $field) {
            $prices[$key] = isset($saved[$field]) ? (int) preg_replace('/\D+/', '', (string) $saved[$field]) : 0;
        }
    }
    return isset($prices[$id]) ? $prices[$id] : 0;
}

function gdpsy_rub($amount)
{
    return number_format((int) $amount, 0, '', "\u{00A0}") . "\u{00A0}₽";
}

function gdpsy_money($id)
{
    $amount = gdpsy_price($id);
    return $amount > 0 ? gdpsy_rub($amount) : '';
}

function gdpsy_plus($id)
{
    $amount = gdpsy_price($id);
    return $amount > 0 ? '+' . gdpsy_rub($amount) : '';
}

function gdpsy_front_id()
{
    return (int) get_option('page_on_front');
}

function gdpsy_selected($field)
{
    $ids = gdpsy_field($field, gdpsy_front_id() ?: false);
    if (!is_array($ids)) {
        return array();
    }
    $out = array();
    foreach ($ids as $item) {
        $id = is_object($item) ? (int) $item->ID : (int) $item;
        if ($id && get_post_status($id) === 'publish') {
            $out[] = $id;
        }
    }
    return $out;
}

function gdpsy_has_concepts()
{
    static $has = null;
    if ($has === null) {
        $has = (bool) gdpsy_selected('concepts');
    }
    return $has;
}

function gdpsy_has_reviews()
{
    $reviews = gdpsy_field('reviews', gdpsy_front_id() ?: false);
    return is_array($reviews) && $reviews;
}

function gdpsy_month()
{
    $months = array('январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
    $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));
    $left = (int) $now->format('t') - (int) $now->format('j');
    $index = (int) $now->format('n') - 1;
    return $months[$left <= 7 ? ($index + 1) % 12 : $index];
}

function gdpsy_moscow_time()
{
    return (new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')))->format('G:i');
}

function gdpsy_phone()
{
    return trim((string) gdpsy_option('phone'));
}

function gdpsy_tel_href()
{
    $phone = gdpsy_phone();
    $digits = preg_replace('/[^\d+]/', '', $phone);
    if ($digits === '') {
        return '';
    }
    if ($digits[0] !== '+') {
        $digits = '+' . $digits;
    }
    return 'tel:' . $digits;
}

function gdpsy_messenger_url($name, $base)
{
    $value = trim((string) gdpsy_option($name));
    if ($value === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $value)) {
        return $value;
    }
    return $base . ltrim($value, '@/');
}

function gdpsy_telegram_url()
{
    return gdpsy_messenger_url('telegram', 'https://t.me/');
}

function gdpsy_max_url()
{
    return gdpsy_messenger_url('max', 'https://max.ru/');
}

function gdpsy_email()
{
    return trim((string) gdpsy_option('email'));
}

function gdpsy_lines($text)
{
    $text = str_replace(array('</p>', '<br>', '<br/>', '<br />'), "\n", (string) $text);
    $lines = preg_split('/\R+/u', wp_strip_all_tags($text));
    return array_values(array_filter(array_map('trim', $lines), 'strlen'));
}

function gdpsy_blog_url()
{
    $page_id = (int) get_option('page_for_posts');
    return $page_id ? get_permalink($page_id) : home_url('/blog/');
}

function gdpsy_primary_term($post_id)
{
    $terms = get_the_category($post_id);
    return $terms ? $terms[0]->name : '';
}

function gdpsy_excerpt($post_id, $words = 18)
{
    return wp_trim_words(get_the_excerpt($post_id), $words, '…');
}

function gdpsy_nav_items()
{
    $portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
    $items = array(
        array('Обо мне', gdpsy_home('#about')),
        array('Лендинг для психолога', gdpsy_home('#includes')),
        array('Доп. модули и стоимость', gdpsy_home('#builder')),
        array('Примеры сайтов', is_front_page() ? '#examples' : $portfolio_url),
    );
    if (gdpsy_has_concepts()) {
        $items[] = array('Концепты', is_front_page() ? '#concepts' : add_query_arg('case-type', 'consept', $portfolio_url));
    }
    $items[] = array('Как будем работать', gdpsy_home('#process'));
    if (gdpsy_has_reviews()) {
        $items[] = array('Отзывы', gdpsy_home('#reviews'));
    }
    $items[] = array('Частые вопросы', gdpsy_home('#faq'));
    $items[] = array('Полезные статьи', gdpsy_blog_url());
    return $items;
}
