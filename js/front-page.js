(function () {
  'use strict';
  if (!document.getElementById('plan') || !document.getElementById('pick'))
    return;

  var GD = window.GD = window.GD || {};
  var fit = GD.fit || function () { };

  var planBox = document.getElementById('plan');
  var CONFIG = {
    name: GD.owner,
    basePrice: Number(planBox.getAttribute('data-base')) || 0,
    conceptPrice: Number(planBox.getAttribute('data-concept')) || 0,
    baseLabel: 'Лендинг под ключ',
    conceptLabel: 'Лендинг на основе концепта'
  };

  var MODULES = [{
    id: 'blog',
    label: 'Блог',
    price: 0
  }, {
    id: 'autopost',
    label: 'Автопубликация',
    price: 0,
    requires: 'blog'
  }, {
    id: 'mail',
    label: 'Email-рассылка',
    price: 0,
    requires: 'blog'
  }, {
    id: 'booking',
    label: 'Онлайн-запись',
    price: 0
  }, {
    id: 'pay',
    label: 'Приём оплаты',
    price: 0
  }, {
    id: 'cabinet',
    label: 'Онлайн-кабинет',
    price: 0
  }, {
    id: 'diary',
    label: 'Дневник клиента',
    price: 0,
    requires: 'cabinet'
  }, {
    id: 'kb',
    label: 'База знаний',
    price: 0
  }, {
    id: 'tests',
    label: 'Тесты для самоанализа',
    price: 0,
    requires: 'kb'
  }, {
    id: 'kb_paid',
    label: 'Платный доступ к базе знаний',
    price: 0,
    requires: ['kb', 'cabinet']
  }, {
    id: 'courses',
    label: 'Продажа курсов',
    price: 0,
    requires: 'cabinet',
    tree: null
  }, {
    id: 'pwa',
    label: 'Приложение из сайта',
    price: 0
  }];

  MODULES.forEach(function (o) {
    var inp = document.querySelector('[data-mod="' + o.id + '"]');
    o.price = inp ? Number(inp.getAttribute('data-cost')) || 0 : 0;
  });

  var $ = function (s, r) {
    return (r || document).querySelector(s);
  };
  var $$ = function (s, r) {
    return Array.prototype.slice.call((r || document).querySelectorAll(s));
  };
  var rub = function (n) {
    return Math.abs(n).toLocaleString('ru-RU').replace(/[\u202f\s]/g, '\u00A0') + '\u00A0₽';
  };
  var money = function (n) {
    return n > 0 ? rub(n) : '';
  };
  var plus = function (n) {
    return n > 0 ? '+' + rub(n) : '';
  };
  var esc = function (s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  };
  var sel = new Set()
    , landing = false
    , onConcept = false
    , conceptBox = document.getElementById('planConcept');
  function basePrice() {
    return onConcept && CONFIG.conceptPrice > 0 ? CONFIG.conceptPrice : CONFIG.basePrice;
  }
  function baseLabel() {
    return onConcept ? CONFIG.conceptLabel : CONFIG.baseLabel;
  }
  function mod(id) {
    for (var i = 0; i < MODULES.length; i++) {
      if (MODULES[i].id === id)
        return MODULES[i];
    }
    return null;
  }
  function reqIds(o) {
    return o && o.requires ? [].concat(o.requires) : [];
  }
  function parentOf(o) {
    return 'tree' in o ? o.tree : (reqIds(o)[0] || null);
  }
  function withParents(set, id) {
    reqIds(mod(id)).forEach(function (p) {
      withParents(set, p);
    });
    set.add(id);
  }
  function dropOrphans(set) {
    var changed = true;
    while (changed) {
      changed = false;
      Array.from(set).forEach(function (id) {
        if (!reqIds(mod(id)).every(function (p) {
          return set.has(p);
        })) {
          set.delete(id);
          changed = true;
        }
      });
    }
  }
  function total(set) {
    var s = basePrice();
    set.forEach(function (id) {
      s += mod(id).price;
    });
    return s;
  }
  function ordered(set) {
    return MODULES.filter(function (o) {
      return set.has(o.id);
    });
  }

  function planMessage() {
    var names = ordered(sel).map(function (o) {
      return o.label;
    });
    return 'Здравствуйте, ' + CONFIG.name + '! Вот мой план сайта:\n• ' + baseLabel() + (names.length ? '\n• ' + names.join('\n• ') : '') + (total(sel) > 0 ? '\nИтого: ' + rub(total(sel)) : '') + '\nДавайте обсудим?';
  }
  function defaultMessage() {
    return landing ? planMessage() : 'Здравствуйте, ' + CONFIG.name + '! Мне нужен сайт, давайте обсудим.';
  }

  var planStart = $('#planStart')
    , planFull = $('#planFull')
    , planTree = $('#planTree')
    , planSum = $('#planSum')
    , mini = $('#miniPlan')
    , miniSum = $('#miniSum')
    , planEl = $('#plan');
  function treeHTML(set) {
    var saved = onConcept && CONFIG.conceptPrice > 0 ? CONFIG.basePrice - CONFIG.conceptPrice : 0;
    var rows = [];
    if (saved > 0)
      rows.push('<li class="plan__save"><small>экономия ' + rub(saved) + '</small></li>');
    rows.push('<li><b>' + esc(baseLabel()) + '</b><span>' + money(basePrice()) + '</span></li>');
    var list = ordered(set);
    list.filter(function (o) {
      return !parentOf(o);
    }).forEach(function (p) {
      rows.push('<li><b>' + esc(p.label) + '</b><span>' + plus(p.price) + '</span></li>');
      list.filter(function (k) {
        return parentOf(k) === p.id;
      }).forEach(function (k) {
        rows.push('<li class="sub">' + esc(k.label) + '<span>' + plus(k.price) + '</span></li>');
      });
    });
    if (!list.length)
      rows.push('<li class="sub plan__empty">модули пока не выбраны</li>');
    return rows.join('');
  }
  function renderPlan() {
    dropOrphans(sel);
    $$('[data-mod]').forEach(function (inp) {
      inp.checked = sel.has(inp.getAttribute('data-mod'));
    });
    $$('[data-card]').forEach(function (c) {
      c.classList.toggle('is-on', !!$('[data-mod]:checked', c));
    });
    planStart.hidden = landing;
    planFull.hidden = !landing;
    if (landing) {
      planTree.innerHTML = treeHTML(sel);
      planSum.textContent = money(total(sel));
      miniSum.textContent = money(total(sel));
    }
    mini.hidden = !landing;
    $$('[data-add-landing]').forEach(function (b) {
      if (b.closest('#planStart'))
        return;
      b.textContent = landing ? 'Посмотреть план сайта' : 'Добавить в план';
      b.classList.toggle('btn--ghost', landing);
      b.classList.toggle('btn--main', !landing);
    });
    $$('[data-msg]', $('#contact')).forEach(function (t) {
      t.value = defaultMessage();
      fit(t);
    });
    updateMini();
  }
  function scrollToPlan() {
    var pad = parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 90;
    window.scrollTo({
      top: planEl.getBoundingClientRect().top + window.scrollY - pad,
      behavior: 'smooth'
    });
  }
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-add-landing]');
    if (!b)
      return;
    if (landing && !b.closest('#planStart')) {
      scrollToPlan();
      return;
    }
    landing = true;
    miniOff = false;
    renderPlan();
  });
  $$('[data-mod]').forEach(function (inp) {
    inp.addEventListener('change', function () {
      var id = inp.getAttribute('data-mod');
      if (inp.checked) {
        withParents(sel, id);
        landing = true;
        miniOff = false;
      } else
        sel.delete(id);
      renderPlan();
    });
  });
  if (conceptBox)
    conceptBox.addEventListener('change', function () {
      onConcept = conceptBox.checked;
      renderPlan();
    });
  $('#planGo').addEventListener('click', function () {
    GD.openSheet(planMessage());
  });
  $('#miniGo').addEventListener('click', scrollToPlan);
  $('#miniX').addEventListener('click', function () {
    miniOff = true;
    updateMini();
  });

  var planVisible = false
    , miniOff = false;
  function updateMini() {
    var away = !landing || miniOff || planVisible || document.body.classList.contains('lock');
    mini.classList.toggle('is-away', away);
    var c = $('#cookie');
    document.documentElement.style.setProperty('--mini-b', (c && !c.hidden ? c.offsetHeight + 24 : 14) + 'px');
    document.body.classList.toggle('has-mini', landing);
  }
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) {
      planVisible = en[0].isIntersecting;
      updateMini();
    }
      , {
        threshold: .2
      }).observe(planEl);
  }
  GD.defaultMessage = defaultMessage;
  document.addEventListener('gd:layout', updateMini);

  var QUIZ = [{
    q: 'Как клиенты записываются к вам сейчас?',
    short: 'Запись',
    a: [{
      t: 'Пишут в мессенджер, и мы долго ищем время',
      s: 'пишут в мессенджер, долго ищем время',
      mods: ['booking', 'pay'],
      why: 'Онлайн-запись и оплата — чтобы не тратить вечера на переписку о времени.'
    }, {
      t: 'Через сервис записи, меня всё устраивает',
      s: 'через сервис записи, всё устраивает',
      why: 'Сервис записи оставляем: на сайте будет кнопка, которая ведёт в него.'
    }, {
      t: 'Я только начинаю практику',
      s: 'только начинаю практику',
      mods: ['booking'],
      why: 'Онлайн-запись с первого дня: человек записывается сам, когда ему удобно.'
    }]
  }, {
    q: 'Пишете ли вы посты или статьи?',
    short: 'Тексты',
    a: [{
      t: 'Да, веду канал или соцсети',
      s: 'веду канал или соцсети',
      mods: ['blog', 'autopost'],
      why: 'Блог — ваши тексты начнут приводить клиентов из поиска, а автопубликация сама отправит анонс статьи в канал.'
    }, {
      t: 'Хочу начать',
      s: 'хочу начать',
      mods: ['blog'],
      why: 'Блог — ваши тексты начнут приводить клиентов из поиска.'
    }, {
      t: 'Нет, это не моё',
      s: 'не пишу'
    }]
  }, {
    q: 'Даёте ли вы клиентам материалы между сессиями?',
    short: 'Материалы',
    a: [{
      t: 'Да: упражнения, тесты, практики',
      s: 'да: упражнения, тесты, практики',
      mods: ['kb', 'tests', 'pwa'],
      why: 'База знаний с тестами соберёт материалы в одном месте, а приложение из сайта позволит клиенту хранить результаты в телефоне и показывать их на встрече.'
    }, {
      t: 'Хотелось бы собрать их в одном месте',
      s: 'хочу собрать в одном месте',
      mods: ['kb'],
      why: 'База знаний — материалы для клиентов будут в одном месте.'
    }, {
      t: 'Нет',
      s: 'нет'
    }]
  }, {
    q: 'Проводите ли вы группы, курсы или интенсивы?',
    short: 'Курсы',
    a: [{
      t: 'Да, уже провожу',
      s: 'уже провожу',
      mods: ['courses', 'pay'],
      why: 'Курсы и интенсивы — страница программы, оплата и доступ к урокам в онлайн-кабинете.'
    }, {
      t: 'Планирую',
      s: 'планирую',
      mods: ['courses'],
      why: 'Продажу курсов можно подключить сразу или позже, когда программа будет готова.'
    }, {
      t: 'Нет',
      s: 'нет'
    }]
  }];
  var qzBox = $('#pick')
    , qzStatus = $('#qzStatus')
    , qz = {
      step: -1,
      ans: [],
      pick: new Set(),
      rec: null
    };
  function optHTML(o, on) {
    return '<label class="opt opt--check' + (on ? ' is-on' : '') + '"><input type="checkbox" name="rec" value="' + esc(o.id) + '"' + (on ? ' checked' : '') + '>' + '<span class="opt__mark"><svg class="ic"><use href="#i-check"/></svg></span>' + '<span class="opt__body"><span class="opt__l">' + esc(o.label) + '</span></span><span class="opt__p">' + plus(o.price) + '</span></label>';
  }
  function qzBuild() {
    var mods = new Set()
      , asked = new Set()
      , why = [];
    qz.ans.forEach(function (ai, qi) {
      var a = QUIZ[qi].a[ai];
      (a.mods || []).forEach(function (id) {
        asked.add(id);
        withParents(mods, id);
      });
      if (a.why)
        why.push(a.why);
    });
    if (mods.has('cabinet') && !asked.has('cabinet'))
      why.push('Онлайн-кабинет добавлен в состав: без него не работают курсы.');
    qz.rec = {
      mods: mods,
      why: why
    };
    qz.pick = new Set(mods);
  }
  function qzRender(focus) {
    var h;
    if (qz.step < 0) {
      h = '<div class="qz__in"><h3>Не знаете, что выбрать?</h3><p class="qz__lead">Четыре вопроса о вашей практике — и я подскажу, какие модули пригодятся.</p>' + '<span class="tag qz__tag">Ответ сразу, без указания контактов</span><button class="btn btn--main btn--sm" type="button" data-q="start">Пройти тест</button></div>';
    } else if (qz.step < QUIZ.length) {
      var q = QUIZ[qz.step];
      h = '<p class="quiz__step">Вопрос ' + (qz.step + 1) + ' из ' + QUIZ.length + '</p>' + '<div class="qz__bar"><i style="width:' + (qz.step / QUIZ.length * 100) + '%"></i></div>' + '<p class="qz__q" tabindex="-1" data-focus>' + esc(q.q) + '</p>' + '<div class="qz__answers">' + q.a.map(function (a, i) {
        return '<button class="qz__a" type="button" data-a="' + i + '">' + esc(a.t) + '</button>';
      }).join('') + '</div>' + (qz.step ? '<button class="link quiz__back" type="button" data-q="prev">Назад</button>' : '');
    } else {
      var r = qz.rec
        , mods = MODULES.filter(function (o) {
          return r.mods.has(o.id);
        });
      h = '<div class="res"><p class="quiz__step">Ваш вариант</p>' + '<h3 tabindex="-1" data-focus>' + (mods.length ? 'Что я рекомендую' : 'Вам хватит лендинга') + '</h3>' + '<p>' + (mods.length ? 'Отметила подходящее по вашим ответам. Снимите лишнее — стоимость пересчитается.' : 'Остальное можно добавить позже, когда появится потребность.') + '</p>' + '<div class="res__base"><div><em>Основа</em><b>' + esc(baseLabel()) + '</b><small>Главная, юридические страницы, аналитика, домен и хостинг.</small></div><span class="p">' + money(basePrice()) + '</span></div>' + (mods.length ? '<div class="opts">' + mods.map(function (o) {
        return optHTML(o, qz.pick.has(o.id));
      }).join('') + '</div>' : '') + (r.why.length ? '<div class="adv__why"><b>Почему так</b>' + r.why.map(function (w) {
        return '<p>' + esc(w) + '</p>';
      }).join('') + '</div>' : '') + '<div class="res__tot"><span>Итого:</span><b data-qsum>' + money(total(qz.pick)) + '</b></div>' + '<p class="res__note">+ домен и хостинг ~1800 ₽ в год</p>' + '<div class="quiz__acts"><button class="btn btn--main" type="button" data-q="apply">Добавить в план</button><button class="btn btn--ghost" type="button" data-q="discuss">Обсудить с Анастасией</button></div>' + '<button class="link quiz__back" type="button" data-q="again">Пройти ещё раз</button>' + '</div>';
    }
    qzBox.innerHTML = h;
    if (qz.step < 0)
      qzStatus.textContent = '';
    else if (qz.step < QUIZ.length)
      qzStatus.textContent = 'Вопрос ' + (qz.step + 1) + ' из ' + QUIZ.length + ': ' + QUIZ[qz.step].short;
    else {
      var recMods = MODULES.filter(function (o) {
        return qz.rec.mods.has(o.id);
      });
      qzStatus.textContent = recMods.length ? 'Результат: рекомендую ' + recMods.map(function (o) {
        return o.label;
      }).join(', ') : 'Результат: лендинга под ключ достаточно';
    }
    var bar = $('.qz__bar i', qzBox);
    if (bar) {
      void bar.offsetWidth;
      bar.style.width = ((qz.step + .5) / QUIZ.length * 100) + '%';
    }
    if (focus) {
      var f = $('[data-focus]', qzBox) || $('button', qzBox);
      if (f)
        f.focus({
          preventScroll: true
        });
    }
  }
  function qzKeep() {
    var r = qzBox.getBoundingClientRect()
      , pad = parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 90;
    if (r.top < pad)
      window.scrollTo({
        top: r.top + window.scrollY - pad,
        behavior: 'smooth'
      });
  }
  function qzMessage() {
    var lines = ['Здравствуйте, ' + CONFIG.name + '! Прошла подбор на сайте.', '', 'О моей практике:'];
    QUIZ.forEach(function (q, i) {
      lines.push('• ' + q.short + ': ' + q.a[qz.ans[i]].s);
    });
    lines.push('', 'Рекомендованный состав:', '• ' + baseLabel());
    ordered(qz.pick).forEach(function (o) {
      lines.push('• ' + o.label);
    });
    if (total(qz.pick) > 0)
      lines.push('Итого: ' + rub(total(qz.pick)));
    lines.push('', 'Давайте обсудим?');
    return lines.join('\n');
  }
  qzBox.addEventListener('click', function (e) {
    var a = e.target.closest('[data-a]');
    if (a) {
      qz.ans[qz.step] = +a.getAttribute('data-a');
      qz.step++;
      if (qz.step === QUIZ.length) {
        qz.ans.length = QUIZ.length;
        qzBuild();
      }
      qzRender(true);
      qzKeep();
      return;
    }
    var b = e.target.closest('[data-q]');
    if (!b)
      return;
    var act = b.getAttribute('data-q');
    if (act === 'start' || act === 'again') {
      qz.step = 0;
      qz.ans = [];
      qzRender(true);
      qzKeep();
      return;
    }
    if (act === 'prev') {
      qz.step--;
      qzRender(true);
      return;
    }
    if (act === 'apply') {
      sel = new Set(qz.pick);
      landing = true;
      miniOff = false;
      renderPlan();
      b.textContent = 'Добавлено в план';
      scrollToPlan();
      return;
    }
    if (act === 'discuss')
      GD.openSheet(qzMessage());
  });
  qzBox.addEventListener('change', function (e) {
    var inp = e.target;
    if (inp.name !== 'rec')
      return;
    if (inp.checked)
      withParents(qz.pick, inp.value);
    else {
      qz.pick.delete(inp.value);
      dropOrphans(qz.pick);
    }
    $$('input[name="rec"]', qzBox).forEach(function (n) {
      n.checked = qz.pick.has(n.value);
      n.closest('.opt').classList.toggle('is-on', n.checked);
    });
    $('[data-qsum]', qzBox).textContent = money(total(qz.pick));
  });
  qzRender(false);
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href="#"]');
    if (a)
      e.preventDefault();
  });

  renderPlan();
})();

(function () {
  'use strict';
  var $ = function (s, r) {
    return (r || document).querySelector(s);
  };
  var $$ = function (s, r) {
    return Array.prototype.slice.call((r || document).querySelectorAll(s));
  };
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var canIO = 'IntersectionObserver' in window;
  function onView(el, fn, thr) {
    if (!el)
      return;
    if (reduce || !canIO) {
      fn();
      return;
    }
    new IntersectionObserver(function (en, obs) {
      en.forEach(function (e) {
        if (e.isIntersecting) {
          fn();
          obs.disconnect();
        }
      });
    }
      , {
        threshold: thr || .35
      }).observe(el);
  }


  (function () {
    var tangle = document.getElementById('tangle');
    var words = document.getElementById('words');
    if (!tangle)
      return;
    var seed = 3;
    var rnd = function () {
      seed = (seed * 16807) % 2147483647;
      return (seed - 1) / 2147483646;
    };
    var cx = 300
      , cy = 92
      , RX = 68
      , RY = 56;
    var x = 210, y = cy + 4, h = 0, k = 0, kt = .05, tick = 0, pts = [[x, y]], hs = [h], i;
    for (i = 0; i < 2600; i++) {
      if (--tick <= 0) {
        tick = 22 + rnd() * 80;
        kt = (kt > 0 ? (rnd() < .7 ? -1 : 1) : (rnd() < .7 ? 1 : -1)) * (.025 + rnd() * .1);
      }
      k += (kt - k) * .08;
      var dx = (x - cx) / RX
        , dy = (y - cy) / RY
        , e = dx * dx + dy * dy;
      if (e > .55) {
        var to = Math.atan2(cy - y, cx - x)
          , df = Math.atan2(Math.sin(to - h), Math.cos(to - h));
        h += df * Math.min(.22, (e - .55) * .18);
      }
      h += k + (rnd() - .5) * .06;
      x += Math.cos(h) * 2;
      y += Math.sin(h) * 2;
      pts.push([x, y]);
      hs.push(h);
    }
    var cut = pts.length - 1;
    for (i = 2000; i < pts.length; i++) {
      if (pts[i][0] > cx + 34 && Math.cos(hs[i]) > .5 && Math.abs(pts[i][1] - cy - 10) < 26) {
        cut = i;
        break;
      }
    }
    pts = pts.slice(0, cut + 1);
    var p1 = pts[cut]
      , u = [Math.cos(hs[cut]), Math.sin(hs[cut])]
      , ex = 390
      , ey = cy + 14
      , bp = [p1[0] + u[0] * 22, p1[1] + u[1] * 22]
      , cp = [ex - 24, ey];
    for (i = 1; i <= 12; i++) {
      var t = i / 12
        , v = 1 - t;
      pts.push([v * v * v * p1[0] + 3 * v * v * t * bp[0] + 3 * v * t * t * cp[0] + t * t * t * ex, v * v * v * p1[1] + 3 * v * v * t * bp[1] + 3 * v * t * t * cp[1] + t * t * t * ey]);
    }
    var d = pts.map(function (p, n) {
      return (n ? 'L' : 'M') + p[0].toFixed(1) + ' ' + p[1].toFixed(1);
    }).join(' ');
    tangle.setAttribute('d', d);
    tangle.style.setProperty('--len', Math.ceil(tangle.getTotalLength()));
    onView(words, function () {
      words.classList.add('is-drawn');
    }, .45);
  }
  )();

  var qa = $$('.qa-row');
  onView($('.qa-chat'), function () {
    qa.forEach(function (r, i) {
      r.style.setProperty('--d', (i * .45) + 's');
      r.classList.add('is-in');
    });
  }, .25);

  var chat = $('#chat')
    , typing = $('#typing');
  if (chat) {
    var bubbles = $$('.bubble', chat);
    var showAll = function () {
      bubbles.forEach(function (b) {
        b.classList.add('is-shown');
      });
    };
    var play = function () {
      var k = 0;
      (function next() {
        if (k >= bubbles.length) {
          typing.classList.remove('is-on');
          return;
        }
        var b = bubbles[k];
        if (b.classList.contains('bubble--in')) {
          typing.style.top = b.offsetTop + 'px';
          typing.classList.add('is-on');
          setTimeout(function () {
            typing.classList.remove('is-on');
            b.classList.add('is-shown');
            k++;
            setTimeout(next, 350);
          }, 900);
        } else {
          b.classList.add('is-shown');
          k++;
          setTimeout(next, 500);
        }
      }
      )();
    };
    if (reduce || !canIO)
      showAll();
    else
      onView(chat, play, .3);
  }

  (function () {
    var wrap = $('#steps');
    if (!wrap)
      return;
    var PH = [[1], [3], [5], [6, 7]];
    var items = $$('.step', wrap)
      , dots = $$('.steps__dots button', wrap)
      , phone = $('#phone')
      , box = $('.phone-box', wrap)
      , stick = $('.steps__stick', wrap);
    var desk = window.matchMedia('(min-width: 960px)');
    var cur = -1
      , t7 = 0
      , ticking = false;
    function setPhone(n) {
      for (var k = 1; k <= 7; k++) {
        phone.classList.toggle('d' + k, k === n);
        if (k > 1)
          phone.classList.toggle('ge' + k, k <= n);
      }
    }
    function syncItem(el, on) {
      if (desk.matches) {
        el.removeAttribute('aria-hidden');
        el.setAttribute('tabindex', '0');
        if (on)
          el.setAttribute('aria-current', 'step');
        else
          el.removeAttribute('aria-current');
      } else {
        el.setAttribute('aria-hidden', String(!on));
        el.removeAttribute('tabindex');
        el.removeAttribute('aria-current');
      }
    }
    function setStep(i) {
      if (i === cur)
        return;
      cur = i;
      clearTimeout(t7);
      items.forEach(function (el, j) {
        var on = j === i;
        el.classList.toggle('is-on', on);
        syncItem(el, on);
      });
      dots.forEach(function (d, j) {
        if (j === i)
          d.setAttribute('aria-current', 'step');
        else
          d.removeAttribute('aria-current');
      });
      setPhone(PH[i][0]);
      if (PH[i][1])
        t7 = setTimeout(function () {
          setPhone(PH[i][1]);
        }, reduce ? 0 : 1700);
    }
    function top0() {
      return parseFloat(getComputedStyle(stick).top) || 0;
    }
    function range() {
      return wrap.offsetHeight - stick.offsetHeight;
    }
    function update() {
      ticking = false;
      if (desk.matches)
        return;
      var r = range();
      if (r <= 0) {
        if (cur < 0)
          setStep(0);
        return;
      }
      var p = Math.min(1, Math.max(0, (top0() - wrap.getBoundingClientRect().top) / r));
      setStep(Math.round(p * (items.length - 1)));
    }
    window.addEventListener('scroll', function () {
      if (!ticking) {
        ticking = true;
        requestAnimationFrame(update);
      }
    }, {
      passive: true
    });
    window.addEventListener('resize', update);
    function syncMode() {
      items.forEach(function (el) {
        syncItem(el, el.classList.contains('is-on'));
      });
      update();
    }
    if (desk.addEventListener)
      desk.addEventListener('change', syncMode);
    else
      desk.addListener(syncMode);
    items.forEach(function (el, j) {
      el.addEventListener('click', function () {
        if (desk.matches)
          setStep(j);
      });
      el.addEventListener('focus', function () {
        if (desk.matches)
          setStep(j);
      });
      el.addEventListener('pointerenter', function (e) {
        if (desk.matches && e.pointerType === 'mouse')
          setStep(j);
      });
      el.addEventListener('keydown', function (e) {
        if (desk.matches && (e.key === 'Enter' || e.key === ' ')) {
          e.preventDefault();
          setStep(j);
        }
      });
    });
    dots.forEach(function (d, j) {
      d.addEventListener('click', function () {
        var y = wrap.getBoundingClientRect().top + window.scrollY - top0() + range() * j / (items.length - 1);
        window.scrollTo({
          top: y + 1,
          behavior: reduce ? 'auto' : 'smooth'
        });
      });
    });
    function fit() {
      phone.style.setProperty('--k', Math.min(box.clientWidth / 270, box.clientHeight / 540).toFixed(4));
    }
    if ('ResizeObserver' in window)
      new ResizeObserver(fit).observe(box);
    else
      window.addEventListener('resize', fit);
    fit();
    update();
    if (cur < 0)
      setStep(0);
    syncMode();
  }
  )();

  var flip = $('#flip');
  if (flip) {
    var faces = [$('#flipBad'), $('#flipGood')]
      , sides = [$('#sideBad'), $('#sideGood')];
    var setFlip = function (on) {
      var had = document.activeElement && document.activeElement.closest('.apr');
      flip.classList.toggle('is-flipped', on);
      [faces, sides].forEach(function (pair) {
        pair.forEach(function (el, k) {
          var vis = (k === 1) === on;
          el.setAttribute('aria-hidden', String(!vis));
          el.inert = !vis;
          el.classList.toggle('is-on', vis);
        });
      });
      var btn = $('.flip__btn', sides[on ? 1 : 0]);
      if (had && btn)
        btn.focus({
          preventScroll: true
        });
    };
    $('.apr').addEventListener('click', function (e) {
      if (e.target.closest('[data-flip]'))
        setFlip(!flip.classList.contains('is-flipped'));
    });
    setFlip(false);
  }

  var wave = $('#wave');
  if (wave && canIO && !reduce) {
    new IntersectionObserver(function (en) {
      en.forEach(function (e) {
        if (!e.isIntersecting)
          return;
        wave.classList.remove('is-waving');
        void wave.offsetWidth;
        wave.classList.add('is-waving');
      });
    }
      , {
        threshold: .6
      }).observe(wave);
  }

  function smoothDetails(list) {
    list.forEach(function (d) {
      d.removeAttribute('name');
      var sm = $('summary', d)
        , anim = null;
      function run(from, to, done) {
        d.style.overflow = 'hidden';
        anim = d.animate({
          height: [from + 'px', to + 'px']
        }, {
          duration: 340,
          easing: 'cubic-bezier(.3,.7,.3,1)'
        });
        anim.onfinish = function () {
          anim = null;
          d.style.overflow = '';
          if (done)
            done();
        }
          ;
      }
      d._close = function () {
        if (!d.open || d.classList.contains('is-closing'))
          return;
        if (reduce || !d.animate) {
          d.open = false;
          return;
        }
        var from = d.getBoundingClientRect().height;
        if (anim) {
          anim.cancel();
          anim = null;
        }
        d.classList.add('is-closing');
        run(from, sm.offsetHeight, function () {
          d.open = false;
          d.classList.remove('is-closing');
        });
      }
        ;
      sm.addEventListener('click', function (e) {
        e.preventDefault();
        if (d.open && !d.classList.contains('is-closing')) {
          d._close();
          return;
        }
        list.forEach(function (o) {
          if (o !== d)
            o._close();
        });
        if (reduce || !d.animate) {
          d.open = true;
          return;
        }
        var from = d.getBoundingClientRect().height;
        if (anim) {
          anim.cancel();
          anim = null;
        }
        d.classList.remove('is-closing');
        d.open = true;
        run(from, d.offsetHeight);
      });
    });
  }
  smoothDetails($$('.kitlist details'));
  smoothDetails($$('#faqList details'));
  $$('.bcard__more').forEach(function (d) {
    smoothDetails([d]);
  });

}
)();

(function () {
  var root = document.getElementById('vdDemo');
  if (!root)
    return;
  var scenes = root.querySelectorAll('.vd-scene');
  var segs = root.querySelectorAll('.vd-seg');
  var caps = root.querySelectorAll('.vd-cap span');
  var pp = root.querySelector('.vd-pp');
  var phone = root.querySelector('.vd-phone');
  var mq = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : {
    matches: false
  };
  var n = scenes.length
    , cur = 0
    , started = false
    , paused = false
    , visible = false;

  function show(i) {
    cur = (i + n) % n;
    for (var k = 0; k < n; k++) {
      scenes[k].classList.remove('is-on');
      segs[k].classList.remove('is-on');
    }
    void root.offsetWidth;
    for (k = 0; k < n; k++) {
      scenes[k].classList.toggle('is-on', k === cur);
      segs[k].classList.toggle('is-on', k === cur);
      segs[k].classList.toggle('is-done', k < cur);
      caps[k].classList.toggle('is-on', k === cur);
      if (k === cur)
        segs[k].setAttribute('aria-current', 'true');
      else
        segs[k].removeAttribute('aria-current');
    }
  }

  function setPaused(p) {
    paused = p;
    root.classList.toggle('is-paused', p);
    pp.setAttribute('aria-pressed', p ? 'true' : 'false');
    pp.setAttribute('aria-label', p ? 'Продолжить показ' : 'Поставить на паузу');
  }

  function go(i) {
    if (!mq.matches && started && paused)
      root.classList.remove('is-run');
    show(i);
  }

  function start() {
    if (started || mq.matches)
      return;
    started = true;
    root.classList.add('is-run');
    show(cur);
  }

  function sync() {
    root.classList.toggle('is-off', !visible || document.hidden);
    if (visible)
      start();
  }

  root.addEventListener('animationend', function (e) {
    if (e.animationName !== 'vd-fill' || !segs[cur].contains(e.target))
      return;
    if (root.classList.contains('is-run') && !paused)
      show(cur + 1);
  });

  Array.prototype.forEach.call(segs, function (s, i) {
    s.addEventListener('click', function () {
      go(i);
    });
  });

  pp.addEventListener('click', function () {
    if (!started) {
      start();
      return;
    }
    if (paused) {
      setPaused(false);
      if (!root.classList.contains('is-run')) {
        root.classList.add('is-run');
        show(cur);
      }
    } else
      setPaused(true);
  });

  var sx = 0
    , sy = 0
    , down = false;
  phone.addEventListener('pointerdown', function (e) {
    down = true;
    sx = e.clientX;
    sy = e.clientY;
  });
  phone.addEventListener('pointercancel', function () {
    down = false;
  });
  phone.addEventListener('pointerup', function (e) {
    if (!down)
      return;
    down = false;
    var dx = e.clientX - sx
      , dy = e.clientY - sy;
    if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
      go(cur + (dx < 0 ? 1 : -1));
      return;
    }
    if (Math.abs(dx) < 10 && Math.abs(dy) < 10) {
      var r = phone.getBoundingClientRect();
      go(cur + (e.clientX - r.left < r.width / 3 ? -1 : 1));
    }
  });

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) {
      visible = en[0].isIntersecting;
      sync();
    }
      , {
        threshold: .35
      }).observe(root);
  } else {
    visible = true;
    sync();
  }
  document.addEventListener('visibilitychange', sync);
}
)();
