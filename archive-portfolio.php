<?php
get_header();
$types = array('project' => 'Проекты', 'consept' => 'Концепты');
$current = isset($_GET['case-type']) ? sanitize_key(wp_unslash($_GET['case-type'])) : '';
$current = isset($types[$current]) ? $current : '';
$present = array();
foreach ($wp_query->posts as $item) {
    $present[(string) gdpsy_field('case-type', $item->ID)] = true;
}
$archive_url = get_post_type_archive_link('portfolio');
?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <header class="inner__head">
      <h1 class="inner__title">Примеры сайтов</h1>
      <p class="inner__lead">Реальные проекты и концепты для разных типов практики.</p>
    </header>
    <?php if (count($present) > 1) : ?>
      <nav class="pfilter" aria-label="Фильтр по типу" data-pfilter>
        <a class="pfilter__btn" href="<?php echo esc_url($archive_url); ?>" data-filter=""<?php echo $current === '' ? ' aria-current="true"' : ''; ?>>Все</a>
        <?php foreach ($types as $value => $label) : ?>
          <?php if (isset($present[$value])) : ?>
            <a class="pfilter__btn" href="<?php echo esc_url(add_query_arg('case-type', $value, $archive_url)); ?>" data-filter="<?php echo esc_attr($value); ?>"<?php echo $current === $value ? ' aria-current="true"' : ''; ?>><?php echo esc_html($label); ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
    <?php if (have_posts()) : ?>
      <div class="pgrid" data-pgrid>
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/card-portfolio', null, array('post_id' => get_the_ID(), 'filter' => $current)); ?>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>Примеров пока нет.</p>
    <?php endif; ?>
    <?php get_template_part('template-parts/cta'); ?>
  </div>
</main>
<?php get_footer(); ?>
