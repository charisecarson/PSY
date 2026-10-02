<?php
$tel = gdpsy_tel_href();
$tg = gdpsy_telegram_url();
$max = gdpsy_max_url();
$logo_href = is_front_page() ? '#top' : home_url('/');
$portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#FBFAF9">
  <script>
    (function (d) {
      var t;
      d.classList.add('js');
      try {
        t = localStorage.getItem('gdpsy_theme');
      } catch (_) { }
      if (t !== 'dark' && t !== 'light')
        t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      d.setAttribute('data-theme', t);
      var m = d.querySelector('meta[name="theme-color"]');
      if (m)
        m.setAttribute('content', t === 'dark' ? '#141517' : '#FBFAF9');
    })(document.documentElement);
  </script>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/sprite'); ?>
<a class="skip" href="#main">Перейти к содержанию</a>
<header class="top" id="header">
  <div class="wrap top__in">
    <a class="logo" href="<?php echo esc_url($logo_href); ?>" aria-label="Greendiz — сайты для психологов<?php echo is_front_page() ? ', наверх' : ''; ?>">
      <span class="logo__mark">
        <svg class="ic" aria-hidden="true">
          <use href="#logo" />
        </svg>
      </span>
      <span class="logo__txt">
        <span class="logo__name">
          Greendiz <span class="logo__psi">ПСИ</span>
        </span>
        <span class="logo__sub">сайты для психологов</span>
      </span>
    </a>
    <button class="iconbtn theme" id="themeBtn" type="button" aria-label="Тёмная тема" aria-pressed="false">
      <svg class="ic i-moon" aria-hidden="true" focusable="false">
        <use href="#i-moon" />
      </svg>
      <svg class="ic i-sun" aria-hidden="true" focusable="false">
        <use href="#i-sun" />
      </svg>
    </button>
    <button class="btn btn--sm top__cta" type="button" data-open-sheet>Обсудить сайт</button>
    <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="menu" aria-label="Меню">
      <svg class="ic i-m" aria-hidden="true" focusable="false">
        <use href="#i-menu" />
      </svg>
      <svg class="ic i-x" aria-hidden="true" focusable="false">
        <use href="#i-x" />
      </svg>
    </button>
  </div>
  <?php get_template_part('template-parts/menu'); ?>
</header>
