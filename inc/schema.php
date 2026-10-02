<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_head', function () {
    if (!is_front_page()) {
        return;
    }

    $provider = array(
        '@type' => 'Person',
        'name' => 'Анастасия Гриндиз',
        'jobTitle' => 'Веб-разработчик',
        'url' => 'https://greendiz.ru/',
    );
    if (gdpsy_phone() !== '') {
        $provider['telephone'] = gdpsy_phone();
    }
    if (gdpsy_email() !== '') {
        $provider['email'] = gdpsy_email();
    }

    $price = gdpsy_price('base');
    $service = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Создание сайта для психолога под ключ',
        'serviceType' => 'Разработка сайтов для психологов, психотерапевтов и коучей',
        'url' => home_url('/'),
        'areaServed' => 'RU',
        'description' => 'Лендинг под ключ за 7 дней: структура, помощь с текстами, дизайн, онлайн-запись, блог, соответствие 152-ФЗ.',
        'provider' => $provider,
    );
    if ($price > 0) {
        $service['offers'] = array(
            '@type' => 'Offer',
            'price' => (string) $price,
            'priceCurrency' => 'RUB',
            'description' => 'Лендинг под ключ, от ' . number_format($price, 0, '', ' ') . ' ₽',
        );
    }
    echo '<script type="application/ld+json">' . wp_json_encode($service, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";

    $faq = gdpsy_field('faq');
    if (!is_array($faq) || !$faq) {
        return;
    }
    $entities = array();
    foreach ($faq as $row) {
        $q = trim(wp_strip_all_tags((string) ($row['vypros'] ?? '')));
        $a = trim(wp_strip_all_tags((string) ($row['otvet'] ?? '')));
        if ($q === '' || $a === '') {
            continue;
        }
        $entities[] = array(
            '@type' => 'Question',
            'name' => $q,
            'acceptedAnswer' => array('@type' => 'Answer', 'text' => $a),
        );
    }
    if ($entities) {
        $page = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        );
        echo '<script type="application/ld+json">' . wp_json_encode($page, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
    }
}, 20);
