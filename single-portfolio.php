<?php
if (is_singular('portfolio') && gdpsy_render_concept(get_queried_object_id())) {
    return;
}
get_header();
?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <?php while (have_posts()) : the_post(); ?>
      <?php
      $type = gdpsy_field('case-type');
      $is_concept = $type === 'consept';
      $info = gdpsy_field($is_concept ? 'consept-info' : 'project-info');
      $info = is_array($info) ? $info : array();
      $mobile = !empty($info['mobile-image']) ? (int) (is_array($info['mobile-image']) ? $info['mobile-image']['ID'] : $info['mobile-image']) : 0;
      $tags = !empty($info['tegi']) && is_array($info['tegi']) ? $info['tegi'] : array();
      $palette = !empty($info['palette']) && is_array($info['palette']) ? $info['palette'] : array();
      $link = $is_concept ? '' : ($info['site-link'] ?? '');
      $facts = array(
          'Задача' => $info['zadacha'] ?? '',
          'Что сделали' => $info['chto_sdelali'] ?? '',
          'Что изменилось' => $info['chto_izmenilos'] ?? '',
      );
      $facts = array_filter($facts, function ($text) {
          return trim((string) $text) !== '';
      });
      $preset = 'Здравствуйте, Анастасия! Мне понравился концепт «' . get_the_title() . '», хочу обсудить его для себя.';
      ?>
      <article <?php post_class(); ?>>
        <header class="inner__head">
          <div class="inner__meta">
            <span class="tag"><?php echo $is_concept ? 'Концепт' : 'Проект'; ?></span>
            <?php if (!empty($info['speczializacziya'])) : ?>
              <span><?php echo esc_html($info['speczializacziya']); ?></span>
            <?php endif; ?>
          </div>
          <h1 class="inner__title"><?php the_title(); ?></h1>
          <?php if (!empty($info['opisanie'])) : ?>
            <p class="inner__lead"><?php echo esc_html(wp_strip_all_tags($info['opisanie'])); ?></p>
          <?php endif; ?>
        </header>
        <?php if (has_post_thumbnail()) : ?>
          <div class="pshots">
            <div class="pshots__desk">
              <?php the_post_thumbnail('large', array('decoding' => 'async')); ?>
            </div>
            <?php if ($mobile) : ?>
              <div class="pshots__mob">
                <?php echo wp_get_attachment_image($mobile, 'medium', false, array('decoding' => 'async', 'alt' => '')); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php if ($tags) : ?>
          <ul class="tags">
            <?php foreach ($tags as $tag) : ?>
              <li><?php echo esc_html(is_array($tag) ? ($tag['label'] ?? $tag['value'] ?? '') : $tag); ?></li>
            <?php endforeach; ?>
          </ul>
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
        <?php if ($facts) : ?>
          <dl class="pfacts">
            <?php foreach ($facts as $label => $text) : ?>
              <div>
                <dt><?php echo esc_html($label); ?></dt>
                <dd><?php echo nl2br(esc_html(wp_strip_all_tags((string) $text))); ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        <?php endif; ?>
        <?php if (get_the_content()) : ?>
          <div class="entry" style="margin-top:36px">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>
        <div class="inner__act">
          <?php if ($is_concept) : ?>
            <button class="btn btn--main" type="button" data-open-sheet
              data-preset="<?php echo esc_attr($preset); ?>">Забрать концепт</button>
          <?php endif; ?>
          <?php if ($link) : ?>
            <a class="link" target="_blank" rel="noopener noreferrer nofollow" href="<?php echo esc_url($link); ?>">
              Открыть сайт
              <svg class="ic" aria-hidden="true" focusable="false">
                <use href="#i-ext" />
              </svg>
            </a>
          <?php endif; ?>
          <a class="link" href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>">Все примеры</a>
        </div>
      </article>
    <?php endwhile; ?>
    <?php get_template_part('template-parts/cta'); ?>
  </div>
</main>
<?php get_footer(); ?>
