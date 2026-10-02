<section class="sec" id="faq" aria-labelledby="faq-title">
  <div class="wrap faq-grid">
    <div class="faq-side">
      <h2 class="sec__title" id="faq-title">Частые вопросы</h2>
      <p class="sec__lead">Не нашли свой вопрос? Напишите — отвечу лично.</p>
      <button class="btn btn--ghost" type="button" style="margin-top:22px" data-open-sheet
        data-preset="Здравствуйте, Анастасия! У меня вопрос о сайте: ">Задать вопрос</button>
      <img class="art faq__art" src="<?php echo gdpsy_img('faq-browser-gears.svg'); ?>"
        alt="Окно браузера с сайтом, за ним шестерёнки и девушка с хвостиком, которая соединяет провода" width="364"
        height="330" loading="lazy" decoding="async">
    </div>
    <div class="faq" id="faqList">
      <?php $faq = gdpsy_field('faq'); ?>
      <?php if (is_array($faq)) : ?>
        <?php foreach ($faq as $row) : ?>
          <details>
            <summary><?php echo esc_html($row['vypros'] ?? ''); ?></summary>
            <p><?php echo nl2br(esc_html($row['otvet'] ?? '')); ?></p>
          </details>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
