<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_management_page('Наполнить главную', 'Наполнить главную', 'manage_options', 'gdpsy-seed', 'gdpsy_seed_page');
});

add_action('admin_post_gdpsy_seed', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав.');
    }
    check_admin_referer('gdpsy_seed');
    $front_id = gdpsy_front_id();
    $done = array();
    if ($front_id && function_exists('update_field')) {
        $data = require get_theme_file_path('inc/seed-data.php');
        foreach ($data as $name => $value) {
            if (!gdpsy_field($name, $front_id)) {
                update_field($name, $value, $front_id);
                $done[] = $name;
            }
        }
    }
    wp_safe_redirect(add_query_arg(array('page' => 'gdpsy-seed', 'done' => implode(',', $done), 'ok' => $front_id ? 1 : 0), admin_url('tools.php')));
    exit;
});

function gdpsy_seed_page()
{
    ?>
    <div class="wrap">
        <h1>Наполнить главную</h1>
        <?php if (isset($_GET['ok'])) : ?>
            <?php if ($_GET['ok'] === '1') : ?>
                <div class="notice notice-success"><p>Заполнено: <?php echo esc_html($_GET['done'] !== '' ? sanitize_text_field(wp_unslash($_GET['done'])) : 'ничего, поля уже заполнены'); ?>.</p></div>
            <?php else : ?>
                <div class="notice notice-error"><p>Главная страница не выбрана в Настройки → Чтение.</p></div>
            <?php endif; ?>
        <?php endif; ?>
        <p>Записывает в пустые поля главной тексты из исходного лендинга: модули конструктора, шаги и отзывы. Заполненные поля не меняются. После заполнения этот файл можно удалить из темы.</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('gdpsy_seed'); ?>
            <input type="hidden" name="action" value="gdpsy_seed">
            <?php submit_button('Заполнить'); ?>
        </form>
    </div>
    <?php
}
