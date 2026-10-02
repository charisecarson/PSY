<form class="searchform" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
  <label class="screen-reader-text" for="s">Поиск</label>
  <input type="search" id="s" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Поиск по сайту">
  <button class="btn btn--main" type="submit">Найти</button>
</form>
