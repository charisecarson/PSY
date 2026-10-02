<section class="band about-band" id="about" aria-labelledby="about-title">
  <div class="wrap about">
    <aside class="about__side">
      <img class="about__photo" src="<?php echo gdpsy_img('about-photo.webp'); ?>" width="560" height="582"
        alt="Анастасия Гриндиз, веб-разработчик" loading="lazy">
      <dl class="about__facts">
        <div>
          <dt><svg class="ic" aria-hidden="true">
              <use href="#i-calendar" />
            </svg>10+</dt>
          <dd>лет в разработке сайтов</dd>
        </div>
        <div>
          <dt><svg class="ic" aria-hidden="true">
              <use href="#i-layers" />
            </svg>60+</dt>
          <dd>реализованных проектов</dd>
        </div>
      </dl>
    </aside>
    <div class="about__body">
      <h2 class="sec__title" id="about-title">
        Привет! <span class="wave" id="wave" aria-hidden="true">👋</span>
        <span class="my_name">Меня зовут Анастасия</span>
      </h2>
      <?php $about = gdpsy_field('about'); ?>
      <?php if ($about) : ?>
        <div class="about__text">
          <?php echo wp_kses_post($about); ?>
        </div>
      <?php endif; ?>
      <?php $chat = gdpsy_field('about_chat'); ?>
      <?php if (is_array($chat) && $chat) : ?>
        <h3 class="qa-h">Отвечу сразу на пару вопросов:</h3>
        <div class="qa-chat">
          <?php foreach ($chat as $row) : ?>
            <?php if (!empty($row['vopros'])) : ?>
              <div class="qa-row">
                <span class="qa-ava" aria-hidden="true">Ψ</span>
                <p class="bub bub--vis"><?php echo nl2br(esc_html($row['vopros'])); ?></p>
              </div>
            <?php endif; ?>
            <?php if (!empty($row['otvet'])) : ?>
              <div class="qa-row qa-row--me">
                <span class="qa-ava" aria-hidden="true">
                  <img src="<?php echo gdpsy_img('about-photo.webp'); ?>" alt="" width="560" height="582" loading="lazy" decoding="async">
                </span>
                <p class="bub bub--me"><?php echo nl2br(esc_html($row['otvet'])); ?></p>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
