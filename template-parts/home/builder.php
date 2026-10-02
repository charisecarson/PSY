<?php $builder = gdpsy_field('builder'); ?>
<section class="sec" id="builder" aria-labelledby="builder-title">
  <div class="wrap">
    <div class="sec__head">
      <h2 class="sec__title" id="builder-title">Можно расширить функционал!</h2>
      <p class="sec__lead">Подключайте модули сразу или позже, когда практика до них дорастёт.</p>
    </div>
    <div class="bld">
      <div class="bcards">
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <path class="i" d="M8 8h22l8 8v28H8z" />
              <path class="i" d="M30 8v8h8M14 23h16M14 29h16M14 35h9" />
              <path class="i" d="M31 38l17-8-6 17-4-6z" fill="var(--white)" />
            </svg>
            <h3>Блог</h3>
          </div>
          <?php $b = $builder['blog'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                Блог<small>со стартовыми статьями</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('blog')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="blog" data-cost="<?php echo (int) gdpsy_price('blog'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Автопубликация<small>анонсы статей в Telegram, VK и MAX; нужен блог</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('autopost')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="autopost" data-cost="<?php echo (int) gdpsy_price('autopost'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Email-рассылка<small>форма подписки и шаблоны писем; нужен блог</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('mail')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="mail" data-cost="<?php echo (int) gdpsy_price('mail'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: блог с рубриками и поиском, а после публикации анонс статьи сам уходит в Telegram, ВКонтакте и MAX"><text
                  class="sc-h" x="12" y="28">ваш блог</text>
                <rect class="sc-l" x="10" y="44" width="250" height="244" rx="16" />
                <circle class="sc-b" cx="28" cy="61" r="3.5" />
                <circle class="sc-b" cx="40" cy="61" r="3.5" />
                <circle class="sc-b" cx="52" cy="61" r="3.5" />
                <path class="sc-o" d="M10 77H260" />
                <rect class="sc-t" x="26" y="92" width="218" height="28" rx="14" />
                <circle class="sc-t2" cx="41" cy="106" r="5" />
                <path class="sc-t2" d="M45 110l4 4" />
                <rect class="sc-b" x="54" y="104" width="96" height="4" rx="2" />
                <rect class="sc-i" x="26" y="132" width="62" height="22" rx="11" /><text x="57.0" y="147"
                  class="sc-w" text-anchor="middle" style="font-size:11px">Тревога</text>
                <rect class="sc-t" x="94" y="132" width="46" height="22" rx="11" /><text x="117.0" y="147"
                  text-anchor="middle" style="font-size:11px">Сон</text>
                <rect class="sc-t" x="146" y="132" width="82" height="22" rx="11" /><text x="187.0" y="147"
                  text-anchor="middle" style="font-size:11px">Выгорание</text>
                <rect class="sc-m" x="26" y="168" width="218" height="50" rx="12" />
                <rect class="sc-i" x="40" y="184" width="124" height="6" rx="3" />
                <rect class="sc-b" x="40" y="199" width="164" height="4" rx="2" />
                <rect class="sc-m" x="26" y="226" width="218" height="50" rx="12" />
                <rect class="sc-i" x="40" y="242" width="104" height="6" rx="3" />
                <rect class="sc-b" x="40" y="257" width="150" height="4" rx="2" />
                <path class="sc-d" d="M262 150C276 150 274 86 288 86" />
                <path class="sc-d" d="M262 166C276 166 274 154 288 154" />
                <path class="sc-d" d="M262 182C276 182 274 222 288 222" />
                <rect class="sc-l" x="288" y="58" width="104" height="56" rx="14" />
                <circle class="sc-m" cx="308" cy="86" r="11" />
                <use class="sc-ic" href="#i-tg" x="301" y="79" width="14" height="14" /><text x="325" y="83"
                  style="font-size:10.5px;font-weight:600">Telegram</text><text x="325" y="98" class="sc-s"
                  style="font-size:9.5px">анонс ушёл</text>
                <rect class="sc-l" x="288" y="126" width="104" height="56" rx="14" />
                <circle class="sc-m" cx="308" cy="154" r="11" /><text x="308" y="158" text-anchor="middle"
                  style="font-size:9px;font-weight:700">VK</text><text x="325" y="151"
                  style="font-size:10.5px;font-weight:600">ВКонтакте</text><text x="325" y="166" class="sc-s"
                  style="font-size:9.5px">анонс ушёл</text>
                <rect class="sc-l" x="288" y="194" width="104" height="56" rx="14" />
                <circle class="sc-m" cx="308" cy="222" r="11" />
                <use class="sc-ic" href="#i-max" x="301" y="215" width="14" height="14" /><text x="325" y="219"
                  style="font-size:10.5px;font-weight:600">MAX</text><text x="325" y="234" class="sc-s"
                  style="font-size:9.5px">анонс ушёл</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <rect class="i" x="6" y="10" width="40" height="36" rx="6" />
              <path class="i" d="M16 5v9M36 5v9M6 20h40" />
              <circle class="f" cx="15" cy="27" r="1.7" />
              <circle class="f" cx="23" cy="27" r="1.7" />
              <circle class="f" cx="31" cy="27" r="1.7" />
              <circle class="f" cx="39" cy="27" r="1.7" />
              <circle class="f" cx="15" cy="36" r="1.7" />
              <path class="i" d="M22 36l5 5 11-12" stroke-width="2.6" />
            </svg>
            <h3>Онлайн-запись и оплата</h3>
          </div>
          <?php $b = $builder['booking'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                Онлайн-запись<small>интеграция с календарём</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('booking')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="booking" data-cost="<?php echo (int) gdpsy_price('booking'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Приём оплаты<small>консультации и курсы оплачиваются на сайте</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('pay')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="pay" data-cost="<?php echo (int) gdpsy_price('pay'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: клиент выбирает день и время в календаре, записывается и сразу оплачивает консультацию"><text
                  class="sc-h" x="12" y="28">без переписки</text>
                <rect class="sc-l" x="10" y="44" width="262" height="244" rx="16" /><text x="28" y="72"
                  style="font-size:13px;font-weight:600">Выберите время</text>
                <rect class="sc-t" x="28" y="86" width="40" height="46" rx="10" /><text x="48" y="103" class="sc-s"
                  text-anchor="middle">пн</text><text x="48" y="122" text-anchor="middle"
                  style="font-size:14px;font-weight:600">6</text>
                <rect class="sc-t" x="74" y="86" width="40" height="46" rx="10" /><text x="94" y="103" class="sc-s"
                  text-anchor="middle">вт</text><text x="94" y="122" text-anchor="middle"
                  style="font-size:14px;font-weight:600">7</text>
                <rect class="sc-i" x="120" y="86" width="40" height="46" rx="10" /><text x="140" y="103"
                  class="sc-w sc-s" text-anchor="middle">ср</text><text x="140" y="122" class="sc-w"
                  text-anchor="middle" style="font-size:14px;font-weight:600">8</text>
                <rect class="sc-t" x="166" y="86" width="40" height="46" rx="10" /><text x="186" y="103"
                  class="sc-s" text-anchor="middle">чт</text><text x="186" y="122" text-anchor="middle"
                  style="font-size:14px;font-weight:600">9</text>
                <rect class="sc-t" x="212" y="86" width="40" height="46" rx="10" /><text x="232" y="103"
                  class="sc-s" text-anchor="middle">пт</text><text x="232" y="122" text-anchor="middle"
                  style="font-size:14px;font-weight:600">10</text>
                <rect class="sc-t" x="28" y="146" width="70" height="28" rx="14" /><text x="63" y="164"
                  text-anchor="middle" style="font-size:12px">10:00</text>
                <rect class="sc-m" x="106" y="146" width="70" height="28" rx="14" /><text x="141" y="164"
                  class="sc-s sc-st" text-anchor="middle">12:00</text>
                <rect class="sc-t" x="184" y="146" width="70" height="28" rx="14" /><text x="219" y="164"
                  text-anchor="middle" style="font-size:12px">14:00</text>
                <rect class="sc-a" x="28" y="182" width="70" height="28" rx="14" /><text x="63" y="200"
                  class="sc-wt" text-anchor="middle" style="font-size:12px;font-weight:600">16:00</text>
                <rect class="sc-t" x="106" y="182" width="70" height="28" rx="14" /><text x="141" y="200"
                  text-anchor="middle" style="font-size:12px">18:00</text>
                <rect class="sc-m" x="184" y="182" width="70" height="28" rx="14" /><text x="219" y="200"
                  class="sc-s sc-st" text-anchor="middle">19:30</text>
                <rect class="sc-i" x="28" y="226" width="226" height="40" rx="20" /><text x="141" y="250"
                  class="sc-w" text-anchor="middle" style="font-size:12.5px;font-weight:600">Записаться на
                  16:00</text>
                <path class="sc-d" d="M274 166H300" /><text class="sc-h" x="294" y="92">оплата</text>
                <rect class="sc-l" x="300" y="104" width="90" height="124" rx="16" />
                <circle class="sc-a" cx="345" cy="138" r="16" />
                <path class="sc-tk" d="M340.5 138l3 3 6-6.5" /><text x="345" y="176" text-anchor="middle"
                  style="font-size:12px;font-weight:600">Оплачено</text><text x="345" y="194" text-anchor="middle"
                  style="font-size:12px">4 000 ₽</text><text x="345" y="212" class="sc-s"
                  text-anchor="middle">карта, СБП</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <circle class="i" cx="26" cy="18" r="8" />
              <path class="i" d="M11 45c0-9 7-15 15-15s15 6 15 15" />
              <rect class="i" x="33" y="30" width="15" height="12" rx="3" />
              <path class="i" d="M36 30v-3a4.5 4.5 0 0 1 9 0v3" />
            </svg>
            <h3>Онлайн-кабинет</h3>
          </div>
          <?php $b = $builder['cabinet'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                Онлайн-кабинет<small>вход для клиентов, доступ к материалам</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('cabinet')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="cabinet" data-cost="<?php echo (int) gdpsy_price('cabinet'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Дневник клиента<small>записи о состоянии между встречами; нужен кабинет</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('diary')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="diary" data-cost="<?php echo (int) gdpsy_price('diary'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: онлайн-кабинет клиента с курсами, записями и закрытыми материалами"><text
                  class="sc-h" x="12" y="28">всё в одном месте</text>
                <rect class="sc-l" x="10" y="44" width="380" height="244" rx="16" />
                <circle class="sc-b" cx="28" cy="61" r="3.5" />
                <circle class="sc-b" cx="40" cy="61" r="3.5" />
                <circle class="sc-b" cx="52" cy="61" r="3.5" />
                <path class="sc-o" d="M10 77H390" />
                <rect class="sc-i" x="22" y="92" width="90" height="28" rx="14" /><text x="67" y="110" class="sc-w"
                  text-anchor="middle" style="font-size:12px;font-weight:600">Курсы</text><text x="34" y="146"
                  class="sc-g" style="font-size:12px">Материалы</text><text x="34" y="176" class="sc-g"
                  style="font-size:12px">Записи</text><text x="34" y="206" class="sc-g"
                  style="font-size:12px">Оплаты</text><text x="34" y="236" class="sc-g"
                  style="font-size:12px">Дневник</text>
                <path class="sc-t" d="M124 92V272" />
                <rect class="sc-m" x="138" y="92" width="238" height="74" rx="14" /><text x="154" y="116"
                  style="font-size:13px;font-weight:600">Курс «Опора»</text><text x="362" y="116" class="sc-s"
                  text-anchor="end">урок 3 из 8</text>
                <rect class="sc-b" x="154" y="130" width="206" height="6" rx="3" />
                <rect class="sc-a" x="154" y="130" width="82" height="6" rx="3" /><text x="154" y="154"
                  class="sc-s">продолжить с урока 3</text>
                <rect class="sc-l2" x="138" y="176" width="238" height="46" rx="14" />
                <circle class="sc-m" cx="161" cy="199" r="12" />
                <use class="sc-ic" href="#i-clock" x="153" y="191" width="16" height="16" /><text x="182" y="196"
                  style="font-size:12px;font-weight:600">8 октября, 16:00</text><text x="182" y="211"
                  class="sc-s">консультация оплачена</text>
                <rect class="sc-l2" x="138" y="230" width="238" height="46" rx="14" />
                <circle class="sc-m" cx="161" cy="253" r="12" />
                <use class="sc-ic" href="#i-lock" x="153" y="245" width="16" height="16" /><text x="182" y="250"
                  style="font-size:12px;font-weight:600">3 закрытые практики</text><text x="182" y="265"
                  class="sc-s">доступ по подписке</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <path class="i" d="M5 13c8-4 16-4 21 2 5-6 13-6 21-2v30c-8-4-16-4-21 2-5-6-13-6-21-2z" />
              <path class="i" d="M26 15v30M10 21h10M10 27h10M31 22l3 3 6-7" />
            </svg>
            <h3>База знаний и тесты</h3>
          </div>
          <?php $b = $builder['kb'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                База знаний<small>памятки и упражнения по темам</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('kb')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="kb" data-cost="<?php echo (int) gdpsy_price('kb'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Тесты для самоанализа<small>раздел базы знаний</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('tests')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="tests" data-cost="<?php echo (int) gdpsy_price('tests'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
            <label class="tgl">
              <span class="tgl__l">
                Платный доступ<small>закрытые материалы по подписке; нужен кабинет</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('kb_paid')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="kb_paid" data-cost="<?php echo (int) gdpsy_price('kb_paid'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <a class="link bcard__link" href="#vdoh">Как это выглядит у клиента: «Вдох» <svg class="ic"
                    aria-hidden="true">
                    <use href="#i-adown" />
                  </svg></a>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: база знаний с рубриками, аудиопрактикой, памяткой, тестом и закрытыми материалами по подписке"><text
                  class="sc-h" x="12" y="28">вы решаете, что там будет</text>
                <rect class="sc-l" x="10" y="44" width="380" height="244" rx="16" />
                <circle class="sc-b" cx="28" cy="61" r="3.5" />
                <circle class="sc-b" cx="40" cy="61" r="3.5" />
                <circle class="sc-b" cx="52" cy="61" r="3.5" />
                <path class="sc-o" d="M10 77H390" /><text x="26" y="100" style="font-size:13px;font-weight:600">База
                  знаний</text>
                <circle class="sc-t2" cx="362" cy="95" r="5" />
                <path class="sc-t2" d="M366 99l4 4" />
                <rect class="sc-i" x="26" y="112" width="42" height="22" rx="11" /><text x="47.0" y="127"
                  class="sc-w" text-anchor="middle" style="font-size:11px">Все</text>
                <rect class="sc-t" x="74" y="112" width="68" height="22" rx="11" /><text x="108.0" y="127"
                  text-anchor="middle" style="font-size:11px">Тревога</text>
                <rect class="sc-t" x="148" y="112" width="46" height="22" rx="11" /><text x="171.0" y="127"
                  text-anchor="middle" style="font-size:11px">Сон</text>
                <rect class="sc-t" x="200" y="112" width="86" height="22" rx="11" /><text x="243.0" y="127"
                  text-anchor="middle" style="font-size:11px">Отношения</text>
                <rect class="sc-l2" x="26" y="148" width="82" height="126" rx="12" />
                <circle class="sc-m" cx="44" cy="166" r="10" />
                <use class="sc-ic" href="#i-play" x="37" y="159" width="14" height="14" />
                <rect class="sc-a" x="34.0" y="192.0" width="3.2" height="8" rx="1.6" />
                <rect class="sc-a" x="40.2" y="187.0" width="3.2" height="18" rx="1.6" />
                <rect class="sc-a" x="46.4" y="183.0" width="3.2" height="26" rx="1.6" />
                <rect class="sc-a" x="52.6" y="189.0" width="3.2" height="14" rx="1.6" />
                <rect class="sc-a" x="58.8" y="181.0" width="3.2" height="30" rx="1.6" />
                <rect class="sc-a" x="65.0" y="186.0" width="3.2" height="20" rx="1.6" />
                <rect class="sc-b" x="71.2" y="190.0" width="3.2" height="12" rx="1.6" />
                <rect class="sc-b" x="77.4" y="184.0" width="3.2" height="24" rx="1.6" />
                <rect class="sc-b" x="83.6" y="188.0" width="3.2" height="16" rx="1.6" />
                <rect class="sc-b" x="89.80000000000001" y="192.0" width="3.2" height="8" rx="1.6" /><text x="34"
                  y="262" style="font-size:11px;font-weight:600">Аудио</text>
                <rect class="sc-l2" x="116" y="148" width="82" height="126" rx="12" />
                <circle class="sc-m" cx="134" cy="166" r="10" />
                <use class="sc-ic" href="#i-file" x="127" y="159" width="14" height="14" />
                <circle class="sc-a" cx="130" cy="188" r="4" />
                <rect class="sc-b" x="140" y="186" width="48" height="4" rx="2" />
                <circle class="sc-a" cx="130" cy="204" r="4" />
                <rect class="sc-b" x="140" y="202" width="48" height="4" rx="2" />
                <circle class="sc-t" cx="130" cy="220" r="4" />
                <rect class="sc-b" x="140" y="218" width="48" height="4" rx="2" /><text x="124" y="262"
                  style="font-size:11px;font-weight:600">Памятки</text>
                <rect class="sc-l2" x="206" y="148" width="82" height="126" rx="12" />
                <circle class="sc-m" cx="224" cy="166" r="10" />
                <use class="sc-ic" href="#i-list" x="217" y="159" width="14" height="14" />
                <rect class="sc-t" x="216" y="186" width="62" height="18" rx="9" />
                <rect class="sc-i" x="216" y="210" width="62" height="18" rx="9" /><text x="214" y="262"
                  style="font-size:11px;font-weight:600">Тесты</text>
                <rect class="sc-dash" x="296" y="148" width="78" height="126" rx="12" />
                <circle class="sc-m" cx="314" cy="166" r="10" />
                <use class="sc-ic" href="#i-lock" x="307" y="159" width="14" height="14" />
                <rect class="sc-b" x="306" y="190" width="56" height="4" rx="2" />
                <rect class="sc-b" x="306" y="202" width="44" height="4" rx="2" />
                <rect class="sc-b" x="306" y="214" width="52" height="4" rx="2" /><text x="304" y="262"
                  style="font-size:11px;font-weight:600">Закрытое</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <rect class="i" x="4" y="9" width="44" height="29" rx="5" />
              <path class="i" d="M26 38v6M17 45h18" />
              <path class="f" d="M22 17l11 6.5-11 6.5z" />
            </svg>
            <h3>Курсы и интенсивы</h3>
          </div>
          <?php $b = $builder['courses'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                Продажа курсов<small>страница, оплата и доступ к урокам; нужен кабинет</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('courses')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="courses" data-cost="<?php echo (int) gdpsy_price('courses'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: клиент оплачивает курс на сайте, и доступ к урокам открывается в его онлайн-кабинете"><text
                  class="sc-h" x="12" y="28">оплатили — доступ открыт</text>
                <rect class="sc-l" x="10" y="44" width="170" height="244" rx="16" /><text x="26" y="74"
                  style="font-size:13px;font-weight:600">Ваш курс</text>
                <circle class="sc-g2" cx="30" cy="98" r="2.5" />
                <rect class="sc-b" x="38" y="96" width="118" height="4" rx="2" />
                <circle class="sc-g2" cx="30" cy="114" r="2.5" />
                <rect class="sc-b" x="38" y="112" width="96" height="4" rx="2" />
                <circle class="sc-g2" cx="30" cy="130" r="2.5" />
                <rect class="sc-b" x="38" y="128" width="108" height="4" rx="2" /><text x="26" y="182"
                  style="font-size:21px;font-weight:700">12 000 ₽</text><text x="26" y="200" class="sc-s">8 уроков,
                  доступ сразу</text>
                <rect class="sc-i" x="26" y="222" width="138" height="40" rx="20" /><text x="95" y="247"
                  class="sc-w" text-anchor="middle" style="font-size:13px;font-weight:600">Оплатить</text>
                <path class="sc-d" d="M184 166H214" />
                <path class="sc-d2" d="M208 160l6 6-6 6" />
                <rect class="sc-l" x="220" y="44" width="170" height="244" rx="16" />
                <circle class="sc-i" cx="242" cy="74" r="12" />
                <use class="sc-ic sc-icw" href="#i-user" x="235" y="67" width="14" height="14" /><text x="262"
                  y="79" style="font-size:13px;font-weight:600">Мои курсы</text>
                <circle class="sc-a" cx="244" cy="110" r="9" />
                <path class="sc-tk" d="M239.5 110l3 3 6-6.5" /><text x="262" y="114" style="font-size:12px">Урок
                  1</text>
                <circle class="sc-a" cx="244" cy="138" r="9" />
                <path class="sc-tk" d="M239.5 138l3 3 6-6.5" /><text x="262" y="142" style="font-size:12px">Урок
                  2</text>
                <circle class="sc-t" cx="244" cy="166" r="9" />
                <use class="sc-ic sc-icl" href="#i-lock" x="238.5" y="160.5" width="11" height="11" /><text x="262"
                  y="170" class="sc-g" style="font-size:12px">Урок 3</text>
                <circle class="sc-t" cx="244" cy="194" r="9" />
                <use class="sc-ic sc-icl" href="#i-lock" x="238.5" y="188.5" width="11" height="11" /><text x="262"
                  y="198" class="sc-g" style="font-size:12px">Урок 4</text>
                <rect class="sc-b" x="236" y="236" width="138" height="6" rx="3" />
                <rect class="sc-a" x="236" y="236" width="36" height="6" rx="3" /><text x="236" y="262"
                  class="sc-s">2 из 8 уроков</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard" data-card>
          <div class="bcard__top">
            <svg class="bcard__icon sketch" viewBox="0 0 52 52" aria-hidden="true">
              <rect class="i" x="14" y="4" width="24" height="44" rx="5" />
              <path class="i" d="M22 9h8M26 16v15M20 25l6 6 6-6M20 39h12" />
            </svg>
            <h3>Приложение из сайта</h3>
          </div>
          <?php $b = $builder['pwa'] ?? array(); ?>
          <?php if (!empty($b['desc'])) : ?>
            <p class="bcard__desc"><?php echo esc_html($b['desc']); ?></p>
          <?php endif; ?>
          <div class="toggles">
            <label class="tgl">
              <span class="tgl__l">
                Приложение из сайта<small>иконка и заставка в вашем стиле</small>
              </span>
              <span class="tgl__p"><?php echo esc_html(gdpsy_plus('pwa')); ?></span>
              <span class="sw">
                <input type="checkbox" data-mod="pwa" data-cost="<?php echo (int) gdpsy_price('pwa'); ?>">
                <span class="sw__track"></span>
              </span>
            </label>
          </div>
          <details class="bcard__more">
            <summary>Как это работает <svg class="ic" aria-hidden="true">
                <use href="#i-adown" />
              </svg></summary>
            <div class="bcard__how">
              <div>
                <?php if (!empty($b['how']) && is_array($b['how'])) : ?>
                <ul class="dots">
                  <?php foreach ($b['how'] as $row) : ?>
                    <li><span><?php echo esc_html($row['text'] ?? ''); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
              <svg class="bcard__art" viewBox="0 0 400 300" role="img"
                aria-label="Схема: сайт установлен на телефон как приложение, в нём дневник клиента и сохранённый результат теста"><text
                  class="sc-h" x="12" y="28">всегда под рукой</text>
                <rect class="sc-l" x="24" y="40" width="150" height="252" rx="24" />
                <rect class="sc-i" x="79" y="49" width="40" height="5" rx="2.5" />
                <rect class="sc-i" x="79" y="280" width="40" height="4" rx="2" />
                <circle class="sc-m" cx="48" cy="78" r="11" />
                <use class="sc-ic" href="#i-heart" x="42" y="72" width="12" height="12" /><text x="64" y="76"
                  style="font-size:10.5px;font-weight:600">Мария Светлова</text><text x="64" y="89" class="sc-s"
                  style="font-size:9px">приложение</text>
                <rect class="sc-i" x="36" y="100" width="60" height="22" rx="11" /><text x="66.0" y="115"
                  class="sc-w" text-anchor="middle" style="font-size:11px">Дневник</text>
                <rect class="sc-t" x="102" y="100" width="48" height="22" rx="11" /><text x="126.0" y="115"
                  text-anchor="middle" style="font-size:11px">Тесты</text>
                <rect class="sc-m" x="36" y="132" width="126" height="42" rx="10" /><text x="46" y="148"
                  class="sc-s" style="font-size:9px">Сегодня</text><text x="46" y="164"
                  style="font-size:10.5px;font-weight:600">Тревога: 4 из 10</text>
                <rect class="sc-m" x="36" y="180" width="126" height="42" rx="10" /><text x="46" y="196"
                  class="sc-s" style="font-size:9px">Вчера</text><text x="46" y="212"
                  style="font-size:10.5px;font-weight:600">Спала 7 часов</text>
                <rect class="sc-l2" x="36" y="228" width="126" height="42" rx="10" /><text x="46" y="244"
                  style="font-size:10px;font-weight:600">Тест на стресс</text>
                <rect class="sc-b" x="46" y="256" width="106" height="4" rx="2" />
                <rect class="sc-a" x="46" y="256" width="44" height="4" rx="2" />
                <path class="sc-d" d="M178 150C196 150 196 128 262 128" />
                <rect class="sc-m" x="208" y="68" width="178" height="166" rx="24" />
                <rect class="sc-b" x="222" y="82" width="32" height="32" rx="9" />
                <rect class="sc-b" x="268" y="82" width="32" height="32" rx="9" />
                <rect class="sc-b" x="314" y="82" width="32" height="32" rx="9" />
                <rect class="sc-b" x="222" y="128" width="32" height="32" rx="9" />
                <rect class="sc-b" x="314" y="128" width="32" height="32" rx="9" />
                <rect class="sc-b" x="222" y="174" width="32" height="32" rx="9" />
                <rect class="sc-b" x="268" y="174" width="32" height="32" rx="9" />
                <rect class="sc-b" x="314" y="174" width="32" height="32" rx="9" />
                <rect class="sc-a" x="268" y="128" width="32" height="32" rx="9" />
                <use class="sc-ic sc-icw2" href="#i-heart" x="276" y="136" width="16" height="16" />
                <rect class="sc-l2" x="240" y="248" width="114" height="30" rx="15" />
                <use class="sc-ic" href="#i-adown" x="254" y="256" width="14" height="14" /><text x="274" y="267"
                  style="font-size:11px;font-weight:600">Установить</text>
              </svg>
            </div>
          </details>
        </article>
        <article class="bcard qz" id="pick">
          <div class="qz__in">
            <h3>Не знаете, что выбрать?</h3>
            <p class="qz__lead">Четыре вопроса о вашей практике — и я подскажу, какие модули пригодятся.</p>
            <span class="tag qz__tag">Ответ сразу, без указания контактов</span>
            <button class="btn btn--main btn--sm" type="button" data-q="start">Пройти тест</button>
          </div>
        </article>
        <p class="visually-hidden" id="qzStatus" aria-live="polite" aria-atomic="true"></p>
        <article class="bcard bcard--idea">
          <h3>Есть своя идея?</h3>
          <p>Расскажите о практике и о том, что хотите получить. Предложу состав, сроки и стоимость — это ни к чему
            не обязывает.</p>
          <button class="btn btn--ghost btn--sm" type="button" data-open-sheet
            data-preset="Здравствуйте, Анастасия! Хочу обсудить сайт и свои идеи для него.">Обсудить сайт</button>
        </article>
      </div>
      <aside class="plan" id="plan" aria-labelledby="plan-title" data-base="<?php echo (int) gdpsy_price('base'); ?>" data-concept="<?php echo (int) gdpsy_price('concept'); ?>">
        <h3 id="plan-title">План вашего сайта</h3>
        <div id="planStart">
          <p class="plan__intro">Начните с основы — лендинга под ключ. Модули можно добавить сразу или позже.</p>
          <button class="btn btn--main btn--block" type="button" data-add-landing>Добавить лендинг</button>
        </div>
        <div id="planFull" hidden aria-live="polite">
          <?php if (gdpsy_has_concepts() && gdpsy_price('concept') > 0) : ?>
            <label class="tgl plan__concept">
              <span class="tgl__l">
                На основе готового концепта<small>лендинг по цене концепта вместо цены «с нуля»</small>
              </span>
              <span class="sw">
                <input type="checkbox" id="planConcept">
                <span class="sw__track"></span>
              </span>
            </label>
          <?php endif; ?>
          <ul class="plan__tree" id="planTree"></ul>
          <div class="plan__sum">
            <strong id="planSum"><?php echo esc_html(gdpsy_money('base')); ?></strong>
            <span id="planNote">+ домен и хостинг ~1800 ₽ в год. Предоплата 50%</span>
          </div>
          <button class="btn btn--main btn--block" type="button" id="planGo">Обсудить этот план</button>
          <a class="link plan__quiz" href="#pick">Не знаете, что выбрать? Четыре вопроса</a>
        </div>
      </aside>
    </div>
  </div>
</section>
