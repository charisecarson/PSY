<?php
if (!function_exists('rank_math_the_breadcrumbs')) {
    return;
}
ob_start();
rank_math_the_breadcrumbs();
$crumbs = trim(ob_get_clean());
if ($crumbs !== '') : ?>
  <div class="crumbs"><?php echo $crumbs; ?></div>
<?php endif; ?>
