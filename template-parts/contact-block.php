<?php
$tel = gdpsy_tel_href();
$tg = gdpsy_telegram_url();
$max = gdpsy_max_url();
$email = gdpsy_email();
$message = isset($args['message']) ? (string) $args['message'] : 'Здравствуйте, Анастасия! Мне нужен сайт, давайте обсудим.';
?>
<div class="cblock">
  <div class="cblock__l">
    <p class="badge">
      <i></i>
      <span>
        Принимаю проекты на <span data-month><?php echo esc_html(gdpsy_month()); ?></span>
      </span>
    </p>
    <h2 class="sec__title">
      Давайте <br class="br3">
      обсудим <br class="br3">ваш сайт
    </h2>
    <p class="cblock__sub">Расскажите о задаче — даже пары предложений достаточно. Отвечу в течение рабочего
      дня с
      вопросами и следующим шагом. Без спама и отдела продаж.</p>
  </div>
  <div class="cblock__r">
    <div class="copybox">
      <textarea class="copybox__text" data-msg rows="5"
        aria-label="Текст сообщения, его можно изменить"><?php echo esc_textarea($message); ?></textarea>
      <button class="btn btn--sm" type="button" data-copy>
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-copy" />
        </svg>
        <span>Копировать сообщение</span>
      </button>
    </div>
    <div class="cact">
      <?php if ($tg) : ?>
        <a class="ccard" href="<?php echo esc_url($tg); ?>" data-link="tg" target="_blank" rel="noopener">
          <span class="ccard__ic">
            <svg class="ic" aria-hidden="true" focusable="false">
              <use href="#i-tg" />
            </svg>
          </span>
          <span class="ccard__t">
            <b>Telegram</b>
            <small>Написать в чат</small>
          </span>
        </a>
      <?php endif; ?>
      <?php if ($max) : ?>
        <a class="ccard" href="<?php echo esc_url($max); ?>" data-link="max" target="_blank" rel="noopener">
          <span class="ccard__ic">
            <svg class="ic" aria-hidden="true" focusable="false">
              <use href="#i-max" />
            </svg>
          </span>
          <span class="ccard__t">
            <b>MAX</b>
            <small>Написать в чат</small>
          </span>
        </a>
      <?php endif; ?>
      <?php if ($tel) : ?>
        <a class="ccard ccard--call" href="<?php echo esc_attr($tel); ?>" data-link="tel">
          <span class="ccard__ic">
            <svg class="ic" aria-hidden="true" focusable="false">
              <use href="#i-phone" />
            </svg>
          </span>
          <span class="ccard__t">
            <b>Позвонить</b>
            <small>Обсудить голосом</small>
          </span>
          <span class="ccard__hrs">
            в будни<br>с 9:00 до 20:00
          </span>
        </a>
      <?php endif; ?>
    </div>
    <div class="cblock__foot">
      <?php if ($email) : ?>
        <p class="cblock__mail">
          Предпочитаете почту? <a
            href="mailto:<?php echo esc_attr($email); ?>?subject=<?php echo rawurlencode('Сайт для психолога'); ?>"
            data-link="mail">Написать email</a>
        </p>
      <?php endif; ?>
      <p class="cblock__note">Отвечаю лично, без ботов</p>
    </div>
  </div>
</div>
