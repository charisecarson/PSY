<?php
$concept_ids = gdpsy_selected('concepts');
if (!$concept_ids) {
    return;
}
$concepts = new WP_Query(array(
    'post_type' => 'portfolio',
    'post_status' => 'publish',
    'post__in' => $concept_ids,
    'orderby' => 'post__in',
    'posts_per_page' => -1,
    'no_found_rows' => true,
));
?>
<section class="sec" id="concepts" aria-labelledby="concepts-title">
  <div class="wrap">
    <div class="concepts">
      <div class="concepts__h">
        <div class="left">
          <h2 class="sec__title" id="concepts-title">Концепты</h2>
          <p>Это не шаблоны. Между проектами я придумываю «идеальный сайт» для определённого типа
            практики — от палитры и шрифтов до иллюстраций и тона текстов — и создаю его таким, как сделала бы для
            себя.</p>
        </div>
        <div class="buyout">
          <p>Каждый концепт существует в одном экземпляре, его можно купить, тогда я сразу сниму его с продажи — он
            будет только ваш. Адаптирую тексты и структуру под вас. Со скидкой :)</p>
        </div>
      </div>
      <?php if ($concepts->have_posts()) : ?>
        <div class="works">
          <?php while ($concepts->have_posts()) : $concepts->the_post(); ?>
            <?php
            $info = gdpsy_field('consept-info');
            $info = is_array($info) ? $info : array();
            $palette = !empty($info['palette']) && is_array($info['palette']) ? $info['palette'] : array();
            $preset = 'Здравствуйте, Анастасия! Мне понравился концепт «' . get_the_title() . '», хочу обсудить его для себя.';
            ?>
            <article class="work">
              <div class="mock">
                <div class="mock__bar">
                  <i></i>
                  <i></i>
                  <i></i>
                </div>
                <div class="mock__pv"<?php echo has_post_thumbnail() ? '' : ' aria-hidden="true"'; ?>>
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title())); ?>
                  <?php endif; ?>
                </div>
              </div>
              <h3><?php the_title(); ?></h3>
              <?php if (!empty($info['speczializacziya'])) : ?>
                <p class="work__for"><?php echo esc_html($info['speczializacziya']); ?></p>
              <?php endif; ?>
              <?php if (!empty($info['opisanie'])) : ?>
                <p class="work__desc"><?php echo esc_html(wp_strip_all_tags($info['opisanie'])); ?></p>
              <?php endif; ?>
              <?php if ($palette) : ?>
                <div class="pal" aria-hidden="true">
                  <?php foreach ($palette as $swatch) : ?>
                    <?php $color = is_array($swatch) ? ($swatch['color'] ?? '') : $swatch; ?>
                    <?php if ($color) : ?>
                      <i style="background:<?php echo esc_attr($color); ?>"></i>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="work__act">
                <button class="btn btn--ghost btn--sm" type="button" data-open-sheet
                  data-preset="<?php echo esc_attr($preset); ?>">Забрать концепт</button>
                <a class="link" href="<?php the_permalink(); ?>">
                  Смотреть
                  <svg class="ic" aria-hidden="true" focusable="false">
                    <use href="#i-ext" />
                  </svg>
                </a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </div>
</section>
