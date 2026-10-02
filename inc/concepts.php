<?php
if (!defined('ABSPATH')) {
    exit;
}

function gdpsy_concepts_dir()
{
    return untrailingslashit(apply_filters('gdpsy_concepts_dir', WP_CONTENT_DIR . '/concepts'));
}

function gdpsy_concept_file($post_id = 0)
{
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'portfolio' || gdpsy_field('case-type', $post->ID) !== 'consept') {
        return '';
    }
    $slug = urldecode($post->post_name);
    if (!preg_match('/^[\p{L}\p{N}_-]+$/u', $slug)) {
        return '';
    }
    $file = gdpsy_concepts_dir() . '/' . $slug . '.html';
    return is_readable($file) ? $file : '';
}

function gdpsy_concept_insert($html, $pattern, $insert, $after = true)
{
    if (!preg_match($pattern, $html, $m, PREG_OFFSET_CAPTURE)) {
        return false;
    }
    $pos = $m[0][1] + ($after ? strlen($m[0][0]) : 0);
    return substr($html, 0, $pos) . $insert . substr($html, $pos);
}

function gdpsy_render_concept($post_id)
{
    $file = gdpsy_concept_file($post_id);
    if ($file === '') {
        return false;
    }
    $html = (string) file_get_contents($file);
    if ($html === '') {
        return false;
    }

    ob_start();
    get_template_part('template-parts/concept-shell', null, array('post_id' => $post_id));
    $shell = ob_get_clean();

    ob_start();
    get_template_part('template-parts/concept-footer', null, array('post_id' => $post_id));
    $footer = ob_get_clean();

    $head = '<meta name="robots" content="noindex, follow">'
        . '<title>' . esc_html(wp_get_document_title()) . '</title>'
        . '<link rel="stylesheet" href="' . esc_url(get_theme_file_uri('css/fonts.css')) . '?ver=' . gdpsy_asset_version('css/fonts.css') . '">'
        . '<style>html.gdd-lock{overflow:hidden}</style>'
        . '<script src="' . esc_url(get_theme_file_uri('js/concept-shell.js')) . '?ver=' . gdpsy_asset_version('js/concept-shell.js') . '" defer></script>';

    if (!preg_match('/<head\b/i', $html)) {
        $doc = '<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . $head . '</head><body>' . $shell . $html . $footer . '</body></html>';
    } else {
        $doc = preg_replace('#<title\b[^>]*>.*?</title>#is', '', $html, 1);
        $doc = gdpsy_concept_insert($doc, '/<head\b[^>]*>/i', $head) ?: $doc;
        $with_shell = gdpsy_concept_insert($doc, '/<body\b[^>]*>/i', $shell);
        if ($with_shell === false) {
            $with_shell = gdpsy_concept_insert($doc, '#</head>#i', $shell);
        }
        $doc = $with_shell !== false ? $with_shell : $doc . $shell;
        $pos = strripos($doc, '</body>');
        $doc = $pos !== false ? substr($doc, 0, $pos) . $footer . substr($doc, $pos) : $doc . $footer;
    }

    status_header(200);
    nocache_headers();
    header('Content-Type: text/html; charset=' . get_option('blog_charset'));
    header('X-Robots-Tag: noindex, follow');
    echo $doc;
    return true;
}

function gdpsy_concept_page()
{
    static $page = null;
    if ($page === null) {
        $page = require get_theme_file_path('inc/concept-defaults.php');
        $saved = gdpsy_option('concept-page');
        if (is_array($saved)) {
            foreach ($page as $key => $default) {
                if (!isset($saved[$key])) {
                    continue;
                }
                $value = $saved[$key];
                if (is_array($default)) {
                    if (!is_array($value) || !$value) {
                        continue;
                    }
                    if ($key === 'offer_list' || $key === 'change_list') {
                        $value = array_values(array_filter(array_map(function ($row) {
                            return trim((string) ($row['text'] ?? ''));
                        }, $value)));
                        if (!$value) {
                            continue;
                        }
                    }
                } elseif (trim((string) $value) === '') {
                    continue;
                }
                $page[$key] = $value;
            }
        }
    }
    return $page;
}

function gdpsy_concept_modules()
{
    $defs = array(
        array('blog', 'Блог', array()),
        array('autopost', 'Автопубликация', array('blog')),
        array('mail', 'Email-рассылка', array('blog')),
        array('booking', 'Онлайн-запись', array()),
        array('pay', 'Приём оплаты', array()),
        array('cabinet', 'Онлайн-кабинет', array()),
        array('diary', 'Дневник клиента', array('cabinet')),
        array('kb', 'База знаний', array()),
        array('tests', 'Тесты для самоанализа', array('kb')),
        array('kb_paid', 'Платный доступ к базе знаний', array('kb', 'cabinet')),
        array('courses', 'Продажа курсов', array('cabinet')),
        array('pwa', 'Приложение из сайта', array()),
    );
    $out = array();
    foreach ($defs as $def) {
        $out[] = array('id' => $def[0], 'label' => $def[1], 'requires' => $def[2], 'price' => gdpsy_price($def[0]));
    }
    return $out;
}

function gdpsy_concept_quiz()
{
    return array(
        array(
            'q' => 'Хотите вести блог?',
            'hint' => 'Статьи приводят клиентов из поиска: вас находят ещё до первого знакомства.',
            'a' => array(
                array('t' => 'Блог со статьями', 'mods' => array('blog')),
                array('t' => 'Анонсы статей сами уходят в Telegram, VK и MAX', 'mods' => array('autopost')),
            ),
        ),
        array(
            'q' => 'Нужна ли онлайн-запись и оплата?',
            'hint' => 'Клиент сам выбирает время и платит, без переписки о свободных окнах.',
            'a' => array(
                array('t' => 'Онлайн-запись на сессии', 'mods' => array('booking')),
                array('t' => 'Приём оплаты на сайте', 'mods' => array('pay')),
            ),
        ),
        array(
            'q' => 'Будете продавать курсы или материалы?',
            'hint' => 'Страница программы, оплата и доступ к урокам в личном кабинете.',
            'a' => array(
                array('t' => 'Курсы и интенсивы', 'mods' => array('courses')),
                array('t' => 'Платный доступ к базе знаний', 'mods' => array('kb_paid')),
            ),
        ),
        array(
            'q' => 'Что нужно клиентам между сессиями?',
            'hint' => 'Материалы, тесты и дневник, которые клиент хранит в одном месте.',
            'a' => array(
                array('t' => 'Личный кабинет клиента', 'mods' => array('cabinet')),
                array('t' => 'Дневник клиента', 'mods' => array('diary')),
                array('t' => 'База знаний с материалами', 'mods' => array('kb')),
                array('t' => 'Тесты для самоанализа', 'mods' => array('tests')),
            ),
        ),
        array(
            'q' => 'Рассылка и приложение на телефоне?',
            'hint' => 'Рассылка возвращает читателей блога, приложение открывается с экрана телефона.',
            'a' => array(
                array('t' => 'Email-рассылка для подписчиков', 'mods' => array('mail')),
                array('t' => 'Сайт как приложение на телефоне', 'mods' => array('pwa')),
            ),
        ),
    );
}

function gdpsy_concept_quiz_data($post_id)
{
    return array(
        'title' => get_the_title($post_id),
        'price' => gdpsy_price('concept'),
        'basePrice' => gdpsy_price('base'),
        'owner' => 'Анастасия',
        'modules' => gdpsy_concept_modules(),
        'questions' => gdpsy_concept_quiz(),
    );
}
