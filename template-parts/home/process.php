<?php
$steps = gdpsy_field('steps');
$steps = is_array($steps) ? array_values($steps) : array();
if (!$steps) {
    return;
}
?>
<section class="sec" id="process" aria-labelledby="process-title">
  <div class="wrap">
    <div class="sec__head steps__head">
      <h2 class="sec__title" id="process-title">Как будем работать</h2>
      <p class="sec__lead">Четыре шага от заявки до запуска. Без скрытых этапов и лишних согласований.</p>
    </div>
    <div class="steps" id="steps">
      <div class="steps__stick">
        <div class="phone-box steps__phone" aria-hidden="true">
          <div class="phone d1" id="phone">
            <div class="phone__screen">
              <div class="pb pb-url">
                <em>…</em>
                <svg viewBox="0 0 12 12" aria-hidden="true" focusable="false">
                  <path d="M3 5V4a3 3 0 0 1 6 0v1M2.5 5h7v5.5h-7z" fill="none" stroke="#1C1C1C"
                    stroke-width="1.3" />
                </svg>
                <b>maria-psy.ru</b>
              </div>
              <div class="pb pb-page"></div>
              <div class="pb pb-nav">
                <i></i>
                <em></em>
              </div>
              <div class="pb pb-title">
                <div class="sk"></div>
                <div class="sk"></div>
                <div class="real">Мария Светлова, психолог</div>
              </div>
              <div class="pb pb-lines">
                <div class="sk"></div>
                <div class="sk"></div>
                <div class="sk"></div>
                <div class="real">Помогаю справляться с тревогой и выгоранием. Онлайн и очно.</div>
              </div>
              <div class="pb pb-art">
                <span>тут будет рисунок</span>
                <svg class="sketch" viewBox="0 0 162 118" style="color:var(--ink)" aria-hidden="true" focusable="false">
                  <path d="M4 112C44 109 110 113 158 110" fill="none" stroke="currentColor" stroke-width="1.4"
                    stroke-linecap="round" />
                  <use href="#armchair" />
                </svg>
              </div>
              <div class="pb pb-btn">
                <span>Записаться</span>
              </div>
              <div class="pb pb-notes">
                <span>с кем работаю?</span>
                <span>мой подход</span>
                <span>цены</span>
                <span>как записаться</span>
              </div>
              <div class="pb pb-checks">
                <div>
                  <svg aria-hidden="true" focusable="false">
                    <use href="#tick" />
                  </svg>
                  форма отправляет заявки
                </div>
                <div>
                  <svg aria-hidden="true" focusable="false">
                    <use href="#tick" />
                  </svg>
                  телефон, планшет, ноутбук
                </div>
                <div>
                  <svg aria-hidden="true" focusable="false">
                    <use href="#tick" />
                  </svg>
                  быстро открывается
                </div>
                <div>
                  <svg aria-hidden="true" focusable="false">
                    <use href="#tick" />
                  </svg>
                  правки внесены
                </div>
              </div>
              <div class="pb pb-live">сайт опубликован!</div>
            </div>
          </div>
        </div>
        <div class="steps__info">
          <div class="steps__body">
            <ol class="steps__list">
              <?php foreach ($steps as $i => $step) : ?>
                <li class="step<?php echo $i === 0 ? ' is-on' : ''; ?>">
                  <h3>
                    <span>Шаг <?php echo (int) ($i + 1); ?>.</span>
                    <?php echo esc_html($step['title'] ?? ''); ?>
                  </h3>
                  <p><?php echo esc_html($step['text'] ?? ''); ?></p>
                </li>
              <?php endforeach; ?>
            </ol>
            <div class="steps__dots" role="group" aria-label="Шаги работы">
              <?php foreach ($steps as $i => $step) : ?>
                <button type="button" aria-label="Шаг <?php echo (int) ($i + 1); ?>: <?php echo esc_attr($step['title'] ?? ''); ?>"<?php echo $i === 0 ? ' aria-current="step"' : ''; ?>></button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="steps__foot">
      <p>7 дней — срок для базового комплекта.<br>Сайт с модулями — 2–3 недели.</p>
    </div>
  </div>
</section>
