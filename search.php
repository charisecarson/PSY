<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <header class="inner__head">
      <h1 class="inner__title">Поиск: <?php echo esc_html(get_search_query()); ?></h1>
      <?php get_search_form(); ?>
    </header>
    <?php if (have_posts()) : ?>
      <div class="pgrid">
        <?php while (have_posts()) : the_post(); ?>
          <?php if (get_post_type() === 'portfolio') : ?>
            <?php get_template_part('template-parts/card-portfolio', null, array('post_id' => get_the_ID())); ?>
          <?php else : ?>
            <?php get_template_part('template-parts/card-post', null, array('post_id' => get_the_ID())); ?>
          <?php endif; ?>
        <?php endwhile; ?>
      </div>
      <?php get_template_part('template-parts/pagination'); ?>
    <?php else : ?>
      <p class="inner__empty">Ничего не найдено. Попробуйте изменить запрос.</p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
