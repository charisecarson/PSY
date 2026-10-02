<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <div class="inner__cols<?php echo is_active_sidebar('blog-sidebar') ? ' has-side' : ''; ?>">
      <div class="inner__main">
      <header class="inner__head">
        <h1 class="inner__title"><?php echo wp_kses_post(get_the_archive_title()); ?></h1>
        <?php if (get_the_archive_description()) : ?>
          <div class="inner__lead"><?php echo wp_kses_post(get_the_archive_description()); ?></div>
        <?php endif; ?>
      </header>
      <?php if (have_posts()) : ?>
        <div class="pgrid">
          <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/card-post', null, array('post_id' => get_the_ID())); ?>
          <?php endwhile; ?>
        </div>
        <?php get_template_part('template-parts/pagination'); ?>
      <?php else : ?>
        <p>В этом разделе пока ничего нет.</p>
      <?php endif; ?>
      </div>
      <?php get_sidebar('blog'); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
