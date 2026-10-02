<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$title = get_the_title($post_id);
$page = gdpsy_concept_page();
$preset = 'Здравствуйте, Анастасия! Мне понравился концепт «' . $title . '», хочу обсудить его для себя.';
$portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
$concepts_url = add_query_arg('case-type', 'consept', $portfolio_url);
$price = gdpsy_money('concept');
$base_price = gdpsy_money('base');
$saving = gdpsy_price('base') - gdpsy_price('concept');
$has_saving = gdpsy_price('concept') > 0 && $saving > 0;
$quiz = gdpsy_concept_quiz_data($post_id);
$css = '';
foreach (array('css/global.css', 'css/concept-shell.css') as $file) {
    $css .= '<link rel="stylesheet" href="' . esc_url(get_theme_file_uri($file)) . '?ver=' . esc_attr(gdpsy_asset_version($file)) . '">';
}
?>
<div id="gdd-foot-host" style="position:relative;z-index:1001">
  <template shadowrootmode="open">
    <?php echo $css; ?>
    <div class="gdd gdd--foot" id="gdd-foot">
      <?php get_template_part('template-parts/sprite'); ?>
      <footer class="gdf" id="gdd-footer">
        <section class="gdf__sec gdf-offer" id="gdd-offer" aria-label="Стоимость и состав сайта">
          <div class="wrap">
            <div class="gdf-pair">
              <article class="gdf-card gdf-card--offer" id="gdd-concept" aria-labelledby="gdd-offer-title">
                <span class="gdd-tag"><?php echo esc_html($page['offer_tag']); ?></span>
                <h2 class="gdf__title" id="gdd-offer-title"><?php echo esc_html($page['offer_title']); ?></h2>
                <p class="gdf__lead"><?php echo esc_html($page['offer_text']); ?></p>
                <?php if ($price) : ?>
                  <div class="gdf-price">
                    <?php if ($has_saving) : ?>
                      <p class="gdf-price__row">
                        <span>Лендинг под ключ</span>
                        <s><?php echo esc_html($base_price); ?></s>
                      </p>
                    <?php endif; ?>
                    <p class="gdf-price__row gdf-price__row--now">
                      <span>Лендинг на основе концепта</span>
                      <b><?php echo esc_html($price); ?></b>
                    </p>
                    <?php if ($has_saving) : ?>
                      <p class="gdf-price__save">Выгода <?php echo esc_html(gdpsy_rub($saving)); ?></p>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
                <div class="gdf-lists">
                  <div>
                    <h3 class="gdf-sub"><?php echo esc_html($page['offer_list_title']); ?></h3>
                    <ul class="gdf-list">
                      <?php foreach ($page['offer_list'] as $item) : ?>
                        <li>
                          <svg class="ic" aria-hidden="true" focusable="false">
                            <use href="#i-check" />
                          </svg>
                          <span><?php echo esc_html($item); ?></span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                  <div id="gdd-change">
                    <h3 class="gdf-sub"><?php echo esc_html($page['change_title']); ?></h3>
                    <ul class="gdf-list">
                      <?php foreach ($page['change_list'] as $item) : ?>
                        <li>
                          <svg class="ic" aria-hidden="true" focusable="false">
                            <use href="#i-check" />
                          </svg>
                          <span><?php echo esc_html($item); ?></span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                    <p class="gdf-hint"><?php echo esc_html($page['change_note']); ?></p>
                  </div>
                </div>
                <div class="gdf__act">
                  <button class="btn btn--main" type="button" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>">Забрать концепт</button>
                </div>
              </article>
              <span class="gdf-plus" aria-hidden="true">+</span>
              <article class="gdf-card gdf-card--quiz" id="gdd-quiz" aria-labelledby="gdd-quiz-title">
                <span class="gdd-tag"><?php echo esc_html($page['quiz_tag']); ?></span>
                <h2 class="gdf__title" id="gdd-quiz-title"><?php echo esc_html($page['quiz_title']); ?></h2>
                <p class="gdf__lead"><?php echo esc_html($page['quiz_lead']); ?></p>
                <div class="gdq" id="gdd-quiz-box" data-quiz="<?php echo esc_attr(wp_json_encode($quiz)); ?>">
                  <button class="btn btn--main" type="button" data-q="start">Пройти тест</button>
                </div>
                <p class="visually-hidden" id="gdd-quiz-status" aria-live="polite"></p>
              </article>
            </div>
            <aside class="gdf-unique" aria-labelledby="gdd-unique-title">
              <div class="gdf-unique__stamp" aria-hidden="true">
                <b>1/1</b>
                <small>экземпляр</small>
              </div>
              <div class="gdf-unique__body">
                <p class="gdf-unique__status">
                  <i aria-hidden="true"></i>
                  <?php echo esc_html($page['unique_status']); ?>
                </p>
                <h3 id="gdd-unique-title"><?php echo esc_html($page['unique_title']); ?></h3>
                <p><?php echo esc_html($page['offer_note']); ?></p>
              </div>
              <button class="btn btn--main" type="button" data-open-sheet data-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($page['unique_button']); ?></button>
            </aside>
          </div>
        </section>
        <section class="gdf__sec gdf-steps" id="gdd-steps" aria-labelledby="gdd-steps-title">
          <div class="wrap">
            <h2 class="gdf__title" id="gdd-steps-title"><?php echo esc_html($page['steps_title']); ?></h2>
            <ol class="gdf-tl">
              <?php foreach ($page['steps'] as $step) : ?>
                <li>
                  <b><?php echo esc_html($step['title'] ?? ''); ?></b>
                  <p><?php echo esc_html($step['text'] ?? ''); ?></p>
                </li>
              <?php endforeach; ?>
            </ol>
          </div>
        </section>
        <section class="gdf__sec gdf-faq-sec" id="gdd-faq" aria-labelledby="gdd-faq-title">
          <div class="wrap">
            <h2 class="gdf__title" id="gdd-faq-title"><?php echo esc_html($page['faq_title']); ?></h2>
            <div class="gdf-faq">
              <?php foreach ($page['faq'] as $row) : ?>
                <details>
                  <summary>
                    <span><?php echo esc_html($row['q'] ?? ''); ?></span>
                    <svg class="ic" aria-hidden="true" focusable="false">
                      <use href="#i-adown" />
                    </svg>
                  </summary>
                  <p><?php echo nl2br(esc_html($row['a'] ?? '')); ?></p>
                </details>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
        <section class="gdf__sec gdf-more" aria-label="Другие концепты">
          <div class="wrap gdf-more__in">
            <p><?php echo esc_html($page['more_text']); ?></p>
            <a class="btn btn--ghost" href="<?php echo esc_url($concepts_url); ?>"><?php echo esc_html($page['more_button']); ?></a>
          </div>
        </section>
        <section class="gdf__sec gdf-contact" id="gdd-contact" aria-label="Связаться">
          <div class="wrap">
            <?php get_template_part('template-parts/contact-block', null, array('message' => $preset)); ?>
          </div>
        </section>
        <div class="gdf-bottom">
          <div class="wrap gdf-bottom__in">
            <nav class="gdf-nav" aria-label="Навигация по сайту">
              <?php foreach (gdpsy_nav_items() as $item) : ?>
                <a href="<?php echo esc_url($item[1]); ?>"><?php echo esc_html($item[0]); ?></a>
              <?php endforeach; ?>
            </nav>
            <nav class="gdf-legal" aria-label="Юридическая информация">
              <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a>
              <a href="<?php echo esc_url(home_url('/agreement/')); ?>">Пользовательское соглашение</a>
              <a href="<?php echo esc_url(home_url('/offer/')); ?>">Оферта</a>
            </nav>
            <p class="gdf-copy">
              © <?php echo esc_html(gmdate('Y')); ?>, сделано в <a href="https://greendiz.ru">greendiz.ru</a>
            </p>
          </div>
        </div>
      </footer>
    </div>
  </template>
</div>
