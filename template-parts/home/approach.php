<section class="sec" id="approach" aria-labelledby="approach-title">
  <div class="wrap apr">
    <div class="sec__head apr__head">
      <h2 class="sec__title" id="approach-title">
        Сайт психолога —<br>не обычный лендинг
      </h2>
      <?php foreach (array_filter(array_map('trim', preg_split('/\R+/u', (string) gdpsy_field('approach_text'))), 'strlen') as $line) : ?>
        <p class="sec__lead"><?php echo esc_html($line); ?></p>
      <?php endforeach; ?>
    </div>
    <div class="flip apr__art" id="flip">
      <div class="flip__in">
        <div class="flip__face" id="flipBad">
          <span class="duo__lbl apr__lbl" aria-hidden="true">давит</span>
          <div class="screen bad" data-flip role="img" aria-label="Пример давящего первого экрана">
            <div class="screen__bar" aria-hidden="true">
              <i></i>
              <i></i>
              <i></i>
              <span>mega-psy.ru</span>
            </div>
            <div class="screen__b" aria-hidden="true">
              <span class="b-timer">Акция сгорит через 14:59</span>
              <p class="b-h">Избавлю от тревоги за 3 сессии!!!</p>
              <p class="b-p">Гарантия результата 100%. Авторская методика!!!</p>
              <div class="b-img">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
                  <path d="M0 0L100 100M100 0L0 100" stroke="#1C1C1C" stroke-width="1"
                    vector-effect="non-scaling-stroke" />
                </svg>
                <span>стоковая улыбка.jpg</span>
                <b class="b-stick">
                  −50%<br>
                  только<br>сегодня
                </b>
              </div>
              <p class="b-price">
                <s>10 000 ₽</s>
                <b>4 990 ₽</b>
              </p>
              <span class="b-btn">Купить пакет сейчас</span>
              <p class="b-foot">Осталось 2 места!!! Успейте, пока не поздно</p>
            </div>
          </div>
        </div>
        <div class="flip__face flip__face--back" id="flipGood" aria-hidden="true" inert>
          <span class="duo__lbl apr__lbl" aria-hidden="true">поддерживает</span>
          <div class="screen good" data-flip role="img" aria-label="Пример бережного первого экрана">
            <div class="screen__bar" aria-hidden="true">
              <i></i>
              <i></i>
              <i></i>
              <span>maria-psy.ru</span>
            </div>
            <div class="screen__b" aria-hidden="true">
              <p class="g-name">
                <span>Мария Светлова, психолог</span>
                <i></i>
              </p>
              <p class="g-h">Помогаю вернуть опору, когда тревожно и всё валится из рук</p>
              <p class="g-price">Сессия 50&nbsp;минут — 4&nbsp;000&nbsp;₽</p>
              <p class="g-btn">
                <span>Выбрать время</span>
              </p>
              <div class="g-scene">
                <div class="g-row">
                  <ul class="g-list">
                    <li>тревога</li>
                    <li>выгорание</li>
                    <li>отношения</li>
                  </ul>
                  <svg class="g-art" viewBox="0 0 146 189" aria-hidden="true" focusable="false">
                    <use href="#armchair-lamp" />
                  </svg>
                </div>
                <svg class="g-floor" viewBox="0 0 300 6" preserveAspectRatio="none" aria-hidden="true" focusable="false">
                  <path d="M1 3C70 2 150 3.5 220 2.6S290 2.6 299 3" vector-effect="non-scaling-stroke" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="apr__side">
      <div class="flip__side is-on" id="sideBad">
        <span class="duo__lbl">давит</span>
        <ul class="xlist">
          <li>
            <svg class="ic" aria-hidden="true">
              <use href="#i-x" />
            </svg>
            <span>Обещания результата и «гарантии»</span>
          </li>
          <li>
            <svg class="ic" aria-hidden="true">
              <use href="#i-x" />
            </svg>
            <span>Таймеры, скидки и «осталось 2 места»</span>
          </li>
          <li>
            <svg class="ic" aria-hidden="true">
              <use href="#i-x" />
            </svg>
            <span>Стоковые фото с чужими улыбками</span>
          </li>
        </ul>
        <button class="flip__btn" type="button" data-flip aria-controls="flip">
          <span class="flip__ic">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M20 11a8 8 0 0 0-14.3-4.9L4 8" />
              <path d="M4 3v5h5" />
              <path d="M4 13a8 8 0 0 0 14.3 4.9L20 16" />
              <path d="M20 21v-5h-5" />
            </svg>
          </span>
          <span>Посмотреть, как надо</span>
        </button>
      </div>
      <div class="flip__side" id="sideGood" aria-hidden="true" inert>
        <span class="duo__lbl">поддерживает</span>
        <ul class="checks">
          <li>
            <svg aria-hidden="true">
              <use href="#tick" />
            </svg>
            <span>Честно о подходе, с чем вы работаете, а с чем — нет</span>
          </li>
          <li>
            <svg aria-hidden="true">
              <use href="#tick" />
            </svg>
            <span>Спокойный темп и понятная стоимость сессии</span>
          </li>
          <li>
            <svg aria-hidden="true">
              <use href="#tick" />
            </svg>
            <span>Живой человек в текстах и фото</span>
          </li>
        </ul>
        <button class="flip__btn flip__btn--icon" type="button" data-flip aria-controls="flip" aria-label="Вернуть">
          <span class="flip__ic">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M20 11a8 8 0 0 0-14.3-4.9L4 8" />
              <path d="M4 3v5h5" />
              <path d="M4 13a8 8 0 0 0 14.3 4.9L20 16" />
              <path d="M20 21v-5h-5" />
            </svg>
          </span>
        </button>
      </div>
    </div>
  </div>
</section>
