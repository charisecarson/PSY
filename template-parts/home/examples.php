<?php
$project_ids = gdpsy_selected('projects');
$projects = new WP_Query(array(
    'post_type' => 'portfolio',
    'post_status' => 'publish',
    'post__in' => $project_ids ?: array(0),
    'orderby' => 'post__in',
    'posts_per_page' => -1,
    'no_found_rows' => true,
));
?>
<section class="sec" id="examples" aria-labelledby="examples-title">
  <div class="wrap">
    <div class="posts__head">
      <div class="sec__head">
        <h2 class="sec__title" id="examples-title">Примеры сайтов</h2>
        <p class="sec__lead">Реальные проекты и концепты для разных типов практики.</p>
      </div>
      <a class="btn btn--ghost btn--sm posts__all" href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>">
        Все проекты
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-arrow" />
        </svg>
      </a>
    </div>
    <?php while ($projects->have_posts()) : $projects->the_post(); ?>
      <?php
      $info = gdpsy_field('project-info');
      $info = is_array($info) ? $info : array();
      $mobile = !empty($info['mobile-image']) ? (int) (is_array($info['mobile-image']) ? $info['mobile-image']['ID'] : $info['mobile-image']) : 0;
      $tags = !empty($info['tegi']) && is_array($info['tegi']) ? $info['tegi'] : array();
      $story = array(
          'С чем пришла' => $info['zadacha'] ?? '',
          'Что сделали' => $info['chto_sdelali'] ?? '',
          'Что изменилось' => $info['chto_izmenilos'] ?? '',
      );
      $story = array_filter($story, function ($text) {
          return trim((string) $text) !== '';
      });
      ?>
      <article class="case">
        <div class="case__top">
          <div class="case__pv">
            <div class="case__desk"<?php echo has_post_thumbnail() ? '' : ' aria-hidden="true"'; ?>>
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', array('loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title())); ?>
              <?php endif; ?>
            </div>
            <div class="case__mob" aria-hidden="true">
              <?php if ($mobile) : ?>
                <?php echo wp_get_attachment_image($mobile, 'medium', false, array('loading' => 'lazy', 'decoding' => 'async', 'alt' => '')); ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="case__info">
            <span class="tag">Проект</span>
            <h3><?php the_title(); ?></h3>
            <?php if (!empty($info['speczializacziya'])) : ?>
              <p class="who"><?php echo esc_html($info['speczializacziya']); ?></p>
            <?php endif; ?>
            <?php if (!empty($info['opisanie'])) : ?>
              <p><?php echo nl2br(esc_html($info['opisanie'])); ?></p>
            <?php endif; ?>
            <?php if ($tags) : ?>
              <ul class="tags">
                <?php foreach ($tags as $tag) : ?>
                  <li><?php echo esc_html(is_array($tag) ? ($tag['label'] ?? $tag['value'] ?? '') : $tag); ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <?php if (!empty($info['site-link'])) : ?>
              <a class="link" target="_blank" rel="noopener noreferrer nofollow" href="<?php echo esc_url($info['site-link']); ?>">
                Открыть сайт
                <svg class="ic" aria-hidden="true" focusable="false">
                  <use href="#i-ext" />
                </svg>
              </a>
            <?php endif; ?>
            <a class="link" href="<?php the_permalink(); ?>">
              О проекте
              <svg class="ic" aria-hidden="true" focusable="false">
                <use href="#i-arrow" />
              </svg>
            </a>
          </div>
        </div>
        <?php if ($story) : ?>
          <dl class="case__story">
            <?php foreach ($story as $label => $text) : ?>
              <div>
                <dt><?php echo esc_html($label); ?></dt>
                <dd><?php echo nl2br(esc_html(wp_strip_all_tags((string) $text))); ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
    <?php get_template_part('template-parts/home/vdoh'); ?>
  </div>
</section>
