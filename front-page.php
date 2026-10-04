<?php get_header(); ?>
<main id="main">
  <?php
  $parts = array('hero', 'pain', 'about', 'approach', 'examples', 'concepts', 'includes', 'builder', 'process', 'reviews', 'faq', 'articles', 'contact');
  foreach ($parts as $part) {
      get_template_part('template-parts/home/' . $part);
  }
  ?>
</main>
<?php get_template_part('template-parts/home/miniplan'); ?>
<?php get_footer(); ?>
