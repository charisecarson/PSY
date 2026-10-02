<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <div class="inner__cols<?php echo is_active_sidebar('blog-sidebar') ? ' has-side' : ''; ?>">
      <div class="inner__main">
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <header class="inner__head">
            <div class="inner__meta">
              <?php $post_tags = get_the_tags(); ?>
              <?php if ($post_tags) : ?>
                <?php foreach ($post_tags as $post_tag) : ?>
                  <a class="tag" href="<?php echo esc_url(get_tag_link($post_tag)); ?>"><?php echo esc_html($post_tag->name); ?></a>
                <?php endforeach; ?>
              <?php endif; ?>
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            </div>
            <h1 class="inner__title"><?php the_title(); ?></h1>
          </header>
          <?php if (has_post_thumbnail()) : ?>
            <figure class="inner__cover">
              <?php the_post_thumbnail('large', array('decoding' => 'async')); ?>
            </figure>
          <?php endif; ?>
          <div class="entry">
            <?php the_content(); ?>
          </div>
          <?php
          $prev = get_previous_post();
          $next = get_next_post();
          ?>
          <?php if ($prev || $next) : ?>
            <nav class="inner__nav" aria-label="Другие статьи">
              <?php if ($prev) : ?>
                <a href="<?php echo esc_url(get_permalink($prev)); ?>" rel="prev"><small>Предыдущая</small><?php echo esc_html(get_the_title($prev)); ?></a>
              <?php endif; ?>
              <?php if ($next) : ?>
                <a href="<?php echo esc_url(get_permalink($next)); ?>" rel="next"><small>Следующая</small><?php echo esc_html(get_the_title($next)); ?></a>
              <?php endif; ?>
            </nav>
          <?php endif; ?>
        </article>
      <?php endwhile; ?>
      </div>
      <?php get_sidebar('blog'); ?>
    </div>
    <?php get_template_part('template-parts/cta'); ?>
  </div>
</main>
<?php get_footer(); ?>
