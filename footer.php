<footer class="foot">
  <div class="wrap foot__in">
    <a class="logo" href="<?php echo esc_url(is_front_page() ? '#top' : home_url('/')); ?>" aria-label="Greendiz — <?php echo is_front_page() ? 'наверх' : 'на главную'; ?>">
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
    <div class="legal">
      <button class="legal__btn" type="button" id="legalBtn" aria-expanded="false"
        aria-controls="legalPop">Юридическая информация</button>
      <nav class="legal__pop" id="legalPop" aria-label="Юридическая информация" hidden>
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a>
        <a href="<?php echo esc_url(home_url('/agreement/')); ?>">Пользовательское соглашение</a>
        <a href="<?php echo esc_url(home_url('/offer/')); ?>">Оферта</a>
      </nav>
    </div>
    <span>
      © <span id="year"><?php echo esc_html(gmdate('Y')); ?></span>
      , сделано в <a href="https://greendiz.ru">greendiz.ru</a>
    </span>
  </div>
</footer>

<?php get_template_part('template-parts/sheet'); ?>

<div class="cookie" id="cookie" role="region" aria-label="Уведомление о cookie" hidden>
  <p>
    Сайт использует <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">cookie</a>.
  </p>
  <button class="btn btn--sm" type="button" data-cookie="all">Ок</button>
</div>
<?php wp_footer(); ?>
</body>

</html>
