<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$home = !empty($args['home']);
$term = gdpsy_primary_term($post_id);
$c = $home ? 'post' : 'pcard';
?>
<a class="<?php echo $c; ?>" href="<?php echo esc_url(get_permalink($post_id)); ?>">
  <div class="<?php echo $c; ?>__pic"<?php echo has_post_thumbnail($post_id) ? '' : ' aria-hidden="true"'; ?>>
    <?php if (has_post_thumbnail($post_id)) : ?>
      <?php echo get_the_post_thumbnail($post_id, 'gd-card', array('loading' => 'lazy', 'decoding' => 'async', 'alt' => '')); ?>
    <?php endif; ?>
  </div>
  <?php if ($term) : ?>
    <span class="tag"><?php echo esc_html($term); ?></span>
  <?php endif; ?>
  <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>
  <p><?php echo esc_html(gdpsy_excerpt($post_id)); ?></p>
  <span class="<?php echo $c; ?>__more">
    Читать
    <svg class="ic" aria-hidden="true" focusable="false">
      <use href="#i-arrow" />
    </svg>
  </span>
</a>
