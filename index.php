<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap">
    <header class="inner__head">
      <h1 class="inner__title"><?php bloginfo('name'); ?></h1>
    </header>
    <?php if (have_posts()) : ?>
      <div class="pgrid">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/card-post', null, array('post_id' => get_the_ID())); ?>
        <?php endwhile; ?>
      </div>
      <?php get_template_part('template-parts/pagination'); ?>
    <?php else : ?>
      <p>Здесь пока ничего нет.</p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
