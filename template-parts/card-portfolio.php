<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$type = gdpsy_field('case-type', $post_id);
$key = $type === 'consept' ? 'consept-info' : 'project-info';
$info = gdpsy_field($key, $post_id);
$info = is_array($info) ? $info : array();
$filter = isset($args['filter']) ? (string) $args['filter'] : '';
$hidden = $filter !== '' && $filter !== (string) $type;
?>
<a class="pcard" href="<?php echo esc_url(get_permalink($post_id)); ?>" data-type="<?php echo esc_attr((string) $type); ?>"<?php echo $hidden ? ' hidden' : ''; ?>>
  <div class="pcard__pic"<?php echo has_post_thumbnail($post_id) ? '' : ' aria-hidden="true"'; ?>>
    <?php if (has_post_thumbnail($post_id)) : ?>
      <?php echo get_the_post_thumbnail($post_id, 'gd-card', array('loading' => 'lazy', 'decoding' => 'async', 'alt' => '')); ?>
    <?php endif; ?>
  </div>
  <span class="tag"><?php echo $type === 'consept' ? 'Концепт' : 'Проект'; ?></span>
  <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>
  <?php if (!empty($info['speczializacziya'])) : ?>
    <p><?php echo esc_html($info['speczializacziya']); ?></p>
  <?php endif; ?>
  <span class="pcard__more">
    Смотреть
    <svg class="ic" aria-hidden="true" focusable="false">
      <use href="#i-arrow" />
    </svg>
  </span>
</a>
