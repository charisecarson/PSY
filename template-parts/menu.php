<?php
$tel = gdpsy_tel_href();
$tg = gdpsy_telegram_url();
$max = gdpsy_max_url();
?>
<nav class="menu" id="menu" aria-label="Навигация по сайту">
  <div class="menu__nav">
    <?php foreach (gdpsy_nav_items() as $item) : ?>
      <a href="<?php echo esc_url($item[1]); ?>"><?php echo esc_html($item[0]); ?></a>
    <?php endforeach; ?>
  </div>
  <div class="menu__contacts">
    <?php if ($tel) : ?>
      <a href="<?php echo esc_attr($tel); ?>" data-link="tel">
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-phone" />
        </svg>
        Позвонить
      </a>
    <?php endif; ?>
    <?php if ($tg) : ?>
      <a href="<?php echo esc_url($tg); ?>" data-link="tg" target="_blank" rel="noopener">
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-tg" />
        </svg>
        Telegram
      </a>
    <?php endif; ?>
    <?php if ($max) : ?>
      <a href="<?php echo esc_url($max); ?>" data-link="max" target="_blank" rel="noopener">
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-max" />
        </svg>
        MAX
      </a>
    <?php endif; ?>
  </div>
  <button class="btn btn--main btn--block" type="button" data-open-sheet>Обсудить сайт</button>
</nav>
