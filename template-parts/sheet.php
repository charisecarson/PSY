<div class="sheet" id="sheet" data-owner="Анастасия" aria-hidden="true">
  <div class="sheet__scrim" data-close-sheet></div>
  <div class="sheet__panel" role="dialog" aria-modal="true" aria-label="Связаться" tabindex="-1">
    <div class="sheet__grab"></div>
    <button class="iconbtn sheet__x" type="button" data-close-sheet aria-label="Закрыть">
      <svg class="ic" aria-hidden="true" focusable="false">
        <use href="#i-x" />
      </svg>
    </button>
    <?php get_template_part('template-parts/contact-block', null, isset($args) && is_array($args) ? $args : array()); ?>
  </div>
</div>
