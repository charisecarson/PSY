<?php
$links = paginate_links(array(
    'type' => 'array',
    'mid_size' => 1,
    'prev_text' => '←',
    'next_text' => '→',
));
if ($links) : ?>
  <nav class="pager" aria-label="Страницы">
    <div class="nav-links">
      <?php foreach ($links as $link) : ?>
        <?php echo $link; ?>
      <?php endforeach; ?>
    </div>
  </nav>
<?php endif; ?>
