<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$title = get_the_title($post_id);
$page = gdpsy_concept_page();
$preset = 'Здравствуйте, Анастасия! Мне понравился концепт «' . $title . '», хочу обсудить его для себя.';
$portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
$concepts_url = add_query_arg('case-type', 'consept', $portfolio_url);
$calculator_url = 'https://psy.greendiz.ru/#builder';

$faq_url = apply_filters(
    'gdpsy_concept_faq_url',
    home_url('/faq/#concepts'),
    $post_id
);

$price = gdpsy_money('concept');
$base_price = gdpsy_money('base');
$saving = gdpsy_price('base') - gdpsy_price('concept');
$has_saving = gdpsy_price('concept') > 0 && $saving > 0;
$quiz = gdpsy_concept_quiz_data($post_id);
$css = '';
foreach (array('css/global.css', 'css/concept-shell.css', 'css/concept-footer.css') as $file) {
    $css .= '<link rel="stylesheet" href="' . esc_url(get_theme_file_uri($file)) . '?ver=' . esc_attr(gdpsy_asset_version($file)) . '">';
}
?>
<div id="gdd-foot-host" style="position:relative;z-index:1001">
  <template shadowrootmode="open">
    <?php echo $css; ?>
    <div class="gdd gdd--foot" id="gdd-foot">
      <?php get_template_part('template-parts/sprite'); ?>
      <section class="gp-concept-footer" aria-label="О концепте">
        <div class="gp-concept-footer__inner">

          <section class="gp-concept-intro" aria-labelledby="gp-concept-title">
              <div class="gp-concept-intro__head">
                  <span class="gp-concept-kicker">Концепт</span>

                  <a class="gp-concept-all" href="<?php echo esc_url($concepts_url); ?>">
                      Все концепты <span aria-hidden="true">→</span>
                  </a>
              </div>

              <h2 class="gp-concept-title" id="gp-concept-title">
                  Этот концепт может стать вашим сайтом.
              </h2>

              <div class="gp-concept-intro__grid">
                  <div>
                      <p class="gp-concept-label">Адаптация</p>
                      <p class="gp-concept-copy">
                          Концепт можно адаптировать под вас: заменить фото и тексты, добавить информацию об образовании, специализации и формате работы. При необходимости подключу нужный функционал — от блога и онлайн-записи до оплаты и других интеграций.
                      </p>
                  </div>

                  <aside class="gp-concept-unique">
                      <strong>Каждый концепт существует в одном экземпляре.</strong>
                      <p>После покупки он уходит из портфолио — такого сайта больше ни у кого не будет.</p>
                  </aside>
              </div>
          </section>

          <section class="gp-concept-next" aria-labelledby="gp-concept-next-title">
              <h2 class="gp-concept-next__title" id="gp-concept-next-title">Что хотите узнать дальше?</h2>

              <div class="gp-concept-accordion">
                  <details class="gp-concept-item">
                      <summary class="gp-concept-trigger">
                          <span class="gp-concept-index">01</span>
                          <span class="gp-concept-question">Можно добавить в этот концепт блог, запись, оплату?</span>
                          <span class="gp-concept-icon" aria-hidden="true"></span>
                      </summary>
                      <div class="gp-concept-panel">
                          <div class="gp-concept-panel__inner">
                              <p>Да, конечно. Вы можете сами выбрать нужные дополнения и посмотреть их стоимость в калькуляторе — или рассказать, что вам нужно, и обсудить задачу со мной.</p>

                              <div class="gp-concept-actions">
                                  <a class="gp-concept-action gp-concept-action--primary" href="<?php echo esc_url($calculator_url); ?>">
                                      Калькулятор <span aria-hidden="true">→</span>
                                  </a>

                                  <button
                                      class="gp-concept-action"
                                      type="button"
                                      data-open-sheet
                                      data-preset="<?php echo esc_attr($preset); ?>"
                                  >
                                      Обсудить <span aria-hidden="true">→</span>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </details>

                  <details class="gp-concept-item">
                      <summary class="gp-concept-trigger">
                          <span class="gp-concept-index">02</span>
                          <span class="gp-concept-question">Что будет после покупки?</span>
                          <span class="gp-concept-icon" aria-hidden="true"></span>
                      </summary>
                      <div class="gp-concept-panel">
                          <div class="gp-concept-panel__inner">
                              <div class="gp-concept-steps">
                                  <div class="gp-concept-step">
                                      <span class="gp-concept-step__number">01</span>
                                      <strong>Выбираете концепт</strong>
                                      <p>Согласуем изменения и дополнения.</p>
                                  </div>
                                  <div class="gp-concept-step">
                                      <span class="gp-concept-step__number">02</span>
                                      <strong>Я адаптирую сайт</strong>
                                      <p>Вношу ваши материалы и нужные правки.</p>
                                  </div>
                                  <div class="gp-concept-step">
                                      <span class="gp-concept-step__number">03</span>
                                      <strong>Настраиваю функционал</strong>
                                      <p>Подключаю всё необходимое.</p>
                                  </div>
                                  <div class="gp-concept-step">
                                      <span class="gp-concept-step__number">04</span>
                                      <strong>Вы получаете готовый сайт</strong>
                                      <p>Проверяем результат и запускаем.</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </details>

                  <details class="gp-concept-item">
                      <summary class="gp-concept-trigger">
                          <span class="gp-concept-index">03</span>
                          <span class="gp-concept-question">У меня много вопросов</span>
                          <span class="gp-concept-icon" aria-hidden="true"></span>
                      </summary>
                      <div class="gp-concept-panel">
                          <div class="gp-concept-panel__inner">
                              <p>Более подробную информацию я собрала в блоке с частыми вопросами.</p>

                              <div class="gp-concept-actions">
                                  <a class="gp-concept-action" href="<?php echo esc_url($faq_url); ?>">
                                      Посмотреть ответы <span aria-hidden="true">→</span>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </details>

                  <details class="gp-concept-item">
                      <summary class="gp-concept-trigger">
                          <span class="gp-concept-index">04</span>
                          <span class="gp-concept-question">Хочу, чтобы этот концепт стал моим сайтом</span>
                          <span class="gp-concept-icon" aria-hidden="true"></span>
                      </summary>
                      <div class="gp-concept-panel">
                          <div class="gp-concept-panel__inner">
                              <p>Отлично. Расскажите немного о себе и о том, каким должен быть ваш сайт.</p>

                              <div class="gp-concept-actions">
                                  <button
                                      class="gp-concept-action gp-concept-action--primary"
                                      type="button"
                                      data-open-sheet
                                      data-preset="<?php echo esc_attr($preset); ?>"
                                  >
                                      Написать <span aria-hidden="true">→</span>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </details>
              </div>
          </section>
        </div>
      </section>

      <footer class="gdf" id="gdd-footer">
        <section class="gdf-contact" id="gdd-contact" aria-label="Связаться">
          <div class="band contact-sec">
            <div class="wrap">
              <?php get_template_part('template-parts/contact-block', null, array_merge(array('message' => $preset), gdpsy_concept_contact_args())); ?>
            </div>
          </div>
        </section>
        <div class="foot">
          <div class="wrap foot__in">
            <a class="logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Greendiz — на главную">
              <span class="logo__mark">
                <svg class="ic" aria-hidden="true" focusable="false">
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
            <div class="legal">
              <button class="legal__btn" type="button" id="legalBtn" aria-expanded="false" aria-controls="legalPop">Юридическая информация</button>
              <nav class="legal__pop" id="legalPop" aria-label="Юридическая информация" hidden>
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a>
                <a href="<?php echo esc_url(home_url('/agreement/')); ?>">Пользовательское соглашение</a>
                <a href="<?php echo esc_url(home_url('/offer/')); ?>">Оферта</a>
              </nav>
            </div>
            <span>
              © <?php echo esc_html(gmdate('Y')); ?>, сделано в <a href="https://greendiz.ru">greendiz.ru</a>
            </span>
          </div>
        </div>
      </footer>
    </div>
  </template>
</div>
