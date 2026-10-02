<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <header class="inner__head">
          <h1 class="inner__title"><?php the_title(); ?></h1>
        </header>
        <div class="entry">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>
