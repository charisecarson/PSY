<section class="sec" id="articles" aria-labelledby="articles-title">
  <div class="wrap">
    <div class="posts__head">
      <div class="sec__head">
        <h2 class="sec__title" id="articles-title">Полезные статьи</h2>
        <p class="sec__lead">О том, как психологу вести сайт: тексты, этика, продвижение.</p>
      </div>
      <a class="btn btn--ghost btn--sm posts__all" href="<?php echo esc_url(gdpsy_blog_url()); ?>">
        Все статьи<span class="posts__w">блога</span>
        <svg class="ic" aria-hidden="true" focusable="false">
          <use href="#i-arrow" />
        </svg>
      </a>
    </div>
    <?php
    $articles = gdpsy_field('articles');
    if (!is_array($articles) || !$articles) {
        $articles = get_posts(array('post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish'));
    }
    ?>
    <?php if ($articles) : ?>
      <div class="posts">
        <?php foreach ($articles as $article) : ?>
          <?php $article_id = is_object($article) ? $article->ID : (int) $article; ?>
          <?php get_template_part('template-parts/card-post', null, array('post_id' => $article_id, 'home' => true)); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
