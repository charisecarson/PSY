<?php get_header(); ?>
<main id="main" class="inner">
  <div class="wrap err">
    <?php get_template_part('template-parts/breadcrumbs'); ?>
    <p class="err__code">404</p>
    <h1 class="inner__title">Страница не найдена</h1>
    <p class="inner__lead" style="margin-inline:auto">Возможно, ссылка устарела или в адресе опечатка.</p>
    <a class="btn btn--main" href="<?php echo esc_url(home_url('/')); ?>">На главную</a>
  </div>
</main>
<?php get_footer(); ?>
