<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$info = gdpsy_field('consept-info', $post_id);
$info = is_array($info) ? $info : array();
$palette = !empty($info['palette']) && is_array($info['palette']) ? $info['palette'] : array();
$title = get_the_title($post_id);
$colors = array();
foreach ($palette as $swatch) {
    $color = is_array($swatch) ? ($swatch['color'] ?? '') : $swatch;
    if ($color) {
        $colors[] = $color;
    }
}
$problem = trim(wp_strip_all_tags((string) ($info['speczializacziya'] ?? '')));
$idea = trim(wp_strip_all_tags((string) ($info['ideya'] ?? '')));
$mech_raw = $info['filtr_mehaniki'] ?? gdpsy_field('filtr_mehaniki', $post_id);
$mech = gdpsy_concept_labels($mech_raw, gdpsy_concept_choices('filtr_mehaniki', 'consept-info', $post_id));
$has_card = $problem || $mech || $colors;
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
          <div class="wrap gdd-intro__grid<?php echo $has_card ? ' has-card' : ''; ?>">
            <div class="gdd-intro__main">
              <nav class="gdd-crumbs" aria-label="Хлебные крошки">
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                <span aria-hidden="true">—</span>
                <a href="<?php echo esc_url(add_query_arg('case-type', 'consept', $portfolio_url)); ?>">Концепты</a>
              </nav>
              <h1 class="gdd-intro__title" id="gdd-title"><?php echo esc_html($title); ?></h1>
              <?php if ($idea) : ?>
                <dl class="gdd-facts">
                  <div>
                    <dt class="gdd-label">Идея</dt>
                    <dd><?php echo nl2br(esc_html($idea)); ?></dd>
                  </div>
                </dl>
              <?php endif; ?>
            </div>
            <?php if ($has_card) : ?>
              <aside class="gdd-card" aria-label="Характеристики концепта">
                <?php if ($problem) : ?>
                  <div>
                    <span class="gdd-label">Проблематика</span>
                    <p class="gdd-card__text"><?php echo esc_html($problem); ?></p>
                  </div>
                <?php endif; ?>
                <?php if ($mech) : ?>
                  <div>
                    <span class="gdd-label">Механики</span>
                    <ul class="gdd-chips">
                      <?php foreach ($mech as $item) : ?>
                        <li class="gdd-chip"><?php echo esc_html($item); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>
                <?php if ($colors) : ?>
                  <div>
                    <span class="gdd-label">Палитра</span>
                    <div class="gdd-pal" aria-hidden="true">
                      <?php foreach ($colors as $color) : ?>
                        <i style="background:<?php echo esc_attr($color); ?>"></i>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </aside>
            <?php endif; ?>
            <div class="gdd-intro__act">
              <button class="btn btn--main" type="button" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>">Хочу такой сайт</button>
              <button class="gdd-view-link" type="button" data-view>
                <svg class="ic" aria-hidden="true" focusable="false">
                  <use href="#i-mobile" />
                </svg>
                <span data-view-text>Посмотреть мобильную версию</span>
              </button>
            </div>
          </div>
        </section>
        <div class="gdd-note">
          <div class="wrap gdd-note__in">
            <svg class="ic" aria-hidden="true" focusable="false">
              <use href="#i-info" />
            </svg>
            <p><?php echo esc_html($page['notice_text']); ?></p>
          </div>
        </div>
      </div>
      <div class="gdd-fab is-hidden" id="fab">
        <div class="gdd-pop" id="pop" role="menu" aria-label="О концепте" hidden>
          <button type="button" role="menuitem" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>">Хочу такой сайт</button>
          <button type="button" role="menuitem" data-goto="#gdd-change">Что можно на нём изменить?</button>
          <button type="button" role="menuitem" data-open-sheet data-preset="<?php echo esc_attr($question); ?>">Задать вопрос</button>
        </div>
        <div class="gdd-fab__row">
          <button class="gdd-view" id="view" type="button" data-view aria-label="Показать мобильную версию" title="Показать мобильную версию">
            <svg class="ic" aria-hidden="true" focusable="false">
              <use href="#i-mobile" />
            </svg>
          </button>
          <button class="gdd-plate" id="plate" type="button" aria-expanded="false" aria-controls="pop">Про концепт</button>
        </div>
      </div>
      <?php get_template_part('template-parts/menu'); ?>
      <?php get_template_part('template-parts/sheet'); ?>
    </div>
  </template>
</div>
