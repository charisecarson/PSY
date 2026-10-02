<?php
$reviews = gdpsy_field('reviews');
if (!is_array($reviews) || !$reviews) {
    return;
}
?>
<section class="band chat-band" id="reviews" aria-labelledby="reviews-title">
  <div class="wrap chat-grid">
    <div>
      <div class="sec__head">
        <span class="note">из переписок с клиентами</span>
        <h2 class="sec__title" id="reviews-title">Что говорят клиенты</h2>
      </div>
      <div class="chat" id="chat">
        <?php foreach ($reviews as $review) : ?>
          <p class="bubble bubble--in">
            <?php echo esc_html($review['text'] ?? ''); ?><?php if (!empty($review['author'])) : ?><cite><?php echo esc_html($review['author']); ?></cite><?php endif; ?>
          </p>
          <?php if (!empty($review['reply'])) : ?>
            <p class="bubble bubble--out"><?php echo esc_html($review['reply']); ?></p>
          <?php endif; ?>
        <?php endforeach; ?>
        <span class="typing" id="typing" aria-hidden="true">
          <i></i>
          <i></i>
          <i></i>
        </span>
      </div>
      <p class="chat__cap">*собирательный образ, основанный на реальности</p>
    </div>
    <img class="chat__art art" src="<?php echo gdpsy_img('chat-girl-lotus.svg'); ?>"
      alt="Девушка с хвостиком парит в позе лотоса над подушкой для медитации, рядом цветок и чёрный кот, над головой облачко с сердцем"
      width="360" height="410" loading="lazy" decoding="async">
  </div>
</section>
