<?php
get_header();
$page_id = (int) get_option('page_for_posts');
$title = $page_id ? get_the_title($page_id) : 'Блог';
$lead = $page_id ? get_post_field('post_excerpt', $page_id) : '';
?>
<main id="main" class="inner">
  <div class="wrap">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <div class="inner__cols<?php echo is_active_sidebar('blog-sidebar') ? ' has-side' : ''; ?>">
      <div class="inner__main">
      <header class="inner__head">
        <h1 class="inner__title"><?php echo esc_html($title); ?></h1>
        <?php if ($lead) : ?>
          <p class="inner__lead"><?php echo esc_html($lead); ?></p>
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
        <p>Статей пока нет.</p>
      <?php endif; ?>
      </div>
      <?php get_sidebar('blog'); ?>
    </div>
    <?php get_template_part('template-parts/cta'); ?>
  </div>
</main>
<?php get_footer(); ?>
