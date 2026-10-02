<section class="sec" id="includes" aria-labelledby="includes-title">
  <div class="wrap incl">
    <div class="incl__l">
      <h2 class="sec__title" id="includes-title">Что должно быть на сайте психолога?</h2>
      <?php $includes_text = trim((string) gdpsy_field('includes_text')); ?>
      <?php if ($includes_text !== '') : ?>
        <p class="sec__lead"><?php echo nl2br(esc_html($includes_text)); ?></p>
      <?php endif; ?>
      <img class="art incl__art" src="<?php echo gdpsy_img('includes-psychologist-laptop.svg'); ?>"
        alt="Психолог с хвостиком сидит в кресле с ноутбуком, от ноутбука и окна браузера нити тянутся к клубку"
        width="520" height="440" loading="lazy" decoding="async">
    </div>
    <div class="kitcard">
      <span class="kitcard__badge">хватает для старта</span>
      <div class="kitcard__head">
        <h3>Лендинг под ключ</h3>
      </div>
      <div class="kitlist">
        <?php $kit = gdpsy_field('kitlist'); ?>
        <?php if (is_array($kit)) : ?>
          <?php foreach ($kit as $i => $row) : ?>
            <details name="kit"<?php echo $i === 0 ? ' open' : ''; ?>>
              <summary>
                <svg aria-hidden="true">
                  <use href="#tick" />
                </svg>
                <span><?php echo esc_html($row['title'] ?? ''); ?></span>
                <i></i>
              </summary>
              <div class="kitlist__body"><?php echo wp_kses_post($row['text'] ?? ''); ?></div>
            </details>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="kitcard__foot">
        <?php if (gdpsy_price('base') > 0) : ?>
          <p>
            <b><?php echo esc_html(gdpsy_money('base')); ?></b>
            <small>+ домен и хостинг ~1800 ₽ в год</small>
          </p>
        <?php endif; ?>
        <p>
          <b>7 дней</b>
          <small>от брифа до запуска</small>
        </p>
      </div>
      <button class="btn btn--main btn--block" type="button" id="addLanding" data-add-landing>Добавить в
        план</button>
    </div>
  </div>
</section>
