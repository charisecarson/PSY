<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$info = gdpsy_field('consept-info', $post_id);
$info = is_array($info) ? $info : array();
$palette = !empty($info['palette']) && is_array($info['palette']) ? $info['palette'] : array();
$title = get_the_title($post_id);
$page = gdpsy_concept_page();
$preset = 'Здравствуйте, Анастасия! Мне понравился концепт «' . $title . '», хочу обсудить его для себя.';
$question = 'Здравствуйте, Анастасия! У меня вопрос по концепту «' . $title . '»: ';
$portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
$css = '';
foreach (array('css/global.css', 'css/concept-shell.css') as $file) {
    $path = get_theme_file_path($file);
    if (is_readable($path)) {
        $css .= file_get_contents($path) . "\n";
    }
}
?>
<div id="gdd-host">
  <template shadowrootmode="open">
    <style><?php echo $css; ?></style>
    <div class="gdd" id="gdd">
      <?php get_template_part('template-parts/sprite'); ?>
      <div class="gdd__head" id="header">
        <div class="top">
          <div class="wrap top__in">
            <a class="logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Greendiz — сайты для психологов, на главную">
              <span class="logo__mark">
                <svg class="ic" aria-hidden="true">
                  <use href="#logo" />
                </svg>
              </span>
              <span class="logo__txt">
                <span class="logo__name">
                  Greendiz <span class="logo__psi">ПСИ</span>
                </span>
                <span class="logo__sub">сайты для психологов</span>
              </span>
            </a>
            <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="menu" aria-label="Меню">
              <svg class="ic i-m" aria-hidden="true" focusable="false">
                <use href="#i-menu" />
              </svg>
              <svg class="ic i-x" aria-hidden="true" focusable="false">
                <use href="#i-x" />
              </svg>
            </button>
          </div>
        </div>
        <section class="gdd-intro" aria-labelledby="gdd-title">
          <div class="wrap">
            <div class="gdd-intro__meta">
              <span class="gdd-tag">Концепт</span>
              <?php if (!empty($info['speczializacziya'])) : ?>
                <span><?php echo esc_html($info['speczializacziya']); ?></span>
              <?php endif; ?>
            </div>
            <h1 class="gdd-intro__title" id="gdd-title"><?php echo esc_html($title); ?></h1>
            <?php if (!empty($info['opisanie'])) : ?>
              <p class="gdd-intro__lead"><?php echo nl2br(esc_html(wp_strip_all_tags($info['opisanie']))); ?></p>
            <?php endif; ?>
            <?php if ($palette) : ?>
              <div class="gdd-pal" aria-hidden="true">
                <?php foreach ($palette as $swatch) : ?>
                  <?php $color = is_array($swatch) ? ($swatch['color'] ?? '') : $swatch; ?>
                  <?php if ($color) : ?>
                    <i style="background:<?php echo esc_attr($color); ?>"></i>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="gdd-intro__act">
              <button class="btn btn--main" type="button" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>">Забрать концепт</button>
              <a class="link" href="<?php echo esc_url(add_query_arg('case-type', 'consept', $portfolio_url)); ?>">Все концепты</a>
            </div>
          </div>
        </section>
        <p class="gdd-note">
          Ниже концепт целиком — листайте как обычный сайт
          <svg class="ic" aria-hidden="true" focusable="false">
            <use href="#i-arrow" />
          </svg>
        </p>
      </div>
      <div class="gdd-hint" id="hint" role="region" aria-label="О концепте" hidden>
        <p><?php echo esc_html($page['notice_text']); ?></p>
        <button class="btn btn--sm" type="button" data-hint="ok"><?php echo esc_html($page['notice_button']); ?></button>
      </div>
      <div class="gdd-fab is-hidden" id="fab">
        <div class="gdd-pop" id="pop" role="menu" aria-label="О концепте" hidden>
          <button type="button" role="menuitem" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>">Хочу такой сайт</button>
          <button type="button" role="menuitem" data-goto="#gdd-change">Что можно на нём изменить?</button>
          <button type="button" role="menuitem" data-open-sheet data-preset="<?php echo esc_attr($question); ?>">Задать вопрос</button>
        </div>
        <button class="gdd-plate" id="plate" type="button" aria-expanded="false" aria-controls="pop">Про концепт</button>
      </div>
      <?php get_template_part('template-parts/menu'); ?>
      <?php get_template_part('template-parts/sheet'); ?>
    </div>
  </template>
</div>
