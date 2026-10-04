(function () {
  'use strict';

  var head = document.getElementById('gdd-host');
  var foot = document.getElementById('gdd-foot-host');
  if (!head) return;

  [head, foot].forEach(function (host) {
    if (!host || host.shadowRoot) return;
    var tpl = host.querySelector('template[shadowrootmode]');
    if (!tpl || !host.attachShadow) return;
    host.attachShadow({ mode: 'open' }).appendChild(tpl.content);
    tpl.remove();
  });

  var roots = [head.shadowRoot];
  if (foot && foot.shadowRoot) roots.push(foot.shadowRoot);
  if (!roots[0]) return;

  function find(s) {
    for (var i = 0; i < roots.length; i++) {
      var n = roots[i].querySelector(s);
      if (n) return n;
    }
    return null;
  }
  function all(s, r) {
    return Array.prototype.slice.call((r || roots[0]).querySelectorAll(s));
  }

  var gdd = find('#gdd');
  var burger = find('#burger');
  var menu = find('#menu');
  var sheet = find('#sheet');
  var fab = find('#fab');
  var plate = find('#plate');
  var pop = find('#pop');
  var hint = find('#hint');
  var HINT_KEY = 'gd_concept_notice';
  var lastFocus = null;
  var html = document.documentElement;

  function lock() {
    var sheetOpen = !!(sheet && sheet.classList.contains('is-open'));
    var menuOpen = !!(menu && menu.classList.contains('is-open'));
    html.classList.toggle('gdd-lock', sheetOpen || menuOpen);
    gdd.classList.toggle('sheet-open', sheetOpen);
  }

  function setMenu(open) {
    if (!burger || !menu) return;
    menu.classList.toggle('is-open', open);
    gdd.classList.toggle('menu-lock', open);
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Закрыть меню' : 'Меню');
    lock();
  }

  function closeHint() {
    if (!hint) return;
    hint.hidden = true;
    try {
      localStorage.setItem(HINT_KEY, '1');
    } catch (_) { }
  }

  function initHint() {
    if (!hint) return;
    var seen = false;
    try {
      seen = !!localStorage.getItem(HINT_KEY);
    } catch (_) { }
    hint.hidden = seen;
  }

  function setPop(open) {
    if (!pop || !plate) return;
    pop.hidden = !open;
    plate.setAttribute('aria-expanded', String(open));
  }

  function fit(t) {
    t.style.height = 'auto';
    if (t.scrollHeight) t.style.height = t.scrollHeight + 'px';
  }

  function openSheet(msg) {
    if (!sheet) return;
    var panel = sheet.querySelector('.sheet__panel');
    var owner = sheet.getAttribute('data-owner') || '';
    lastFocus = document.activeElement;
    all('[data-msg]', sheet).forEach(function (t) {
      t.value = msg || 'Здравствуйте, ' + owner + '! Мне нужен сайт, давайте обсудим.';
    });
    setMenu(false);
    setPop(false);
    sheet.classList.add('is-open');
    sheet.setAttribute('aria-hidden', 'false');
    lock();
    setTimeout(function () {
      panel.scrollTop = 0;
      panel.focus({ preventScroll: true });
      all('[data-msg]', panel).forEach(fit);
    }, 60);
  }

  function closeSheet() {
    if (!sheet || !sheet.classList.contains('is-open')) return;
    sheet.classList.remove('is-open');
    sheet.setAttribute('aria-hidden', 'true');
    lock();
    if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }

  function copyText(b) {
    var text = b.closest('.cblock').querySelector('[data-msg]').value;
    var label = b.querySelector('span');
    var use = b.querySelector('use');
    function done(ok) {
      label.textContent = ok ? 'Скопировано' : 'Выделите и скопируйте';
      if (ok) use.setAttribute('href', '#i-check');
      clearTimeout(b._t);
      b._t = setTimeout(function () {
        label.textContent = 'Копировать сообщение';
        use.setAttribute('href', '#i-copy');
      }, 2200);
    }
    function fallback() {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.setAttribute('readonly', '');
      ta.style.cssText = 'position:fixed;opacity:0';
      document.body.appendChild(ta);
      ta.select();
      var ok = false;
      try {
        ok = document.execCommand('copy');
      } catch (_) { }
      document.body.removeChild(ta);
      done(ok);
    }
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(function () {
        done(true);
      }, fallback);
    } else {
      fallback();
    }
  }

  var quizBox = find('#gdd-quiz-box');
  var quizStatus = find('#gdd-quiz-status');
  var quiz = null;
  var qz = { step: -1, ans: [], pick: new Set() };

  if (quizBox) {
    try {
      quiz = JSON.parse(quizBox.getAttribute('data-quiz'));
    } catch (_) { }
  }

  function h(tag, cls, text) {
    var n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text != null) n.textContent = text;
    return n;
  }

  function btn(cls, text, attr, val) {
    var b = h('button', cls, text);
    b.type = 'button';
    b.setAttribute(attr, val);
    return b;
  }

  function rub(n) {
    return n.toLocaleString('ru-RU').replace(/[ \s]/g, ' ') + ' ₽';
  }

  function moduleById(id) {
    return quiz.modules.filter(function (m) {
      return m.id === id;
    })[0];
  }

  function addWithParents(set, id) {
    var m = moduleById(id);
    if (!m || set.has(id)) return;
    m.requires.forEach(function (p) {
      addWithParents(set, p);
    });
    set.add(id);
  }

  function dropOrphans(set) {
    var changed = true;
    while (changed) {
      changed = false;
      Array.from(set).forEach(function (id) {
        var m = moduleById(id);
        if (m && !m.requires.every(function (p) {
          return set.has(p);
        })) {
          set.delete(id);
          changed = true;
        }
      });
    }
  }

  function orderedMods(set) {
    return quiz.modules.filter(function (m) {
      return set.has(m.id);
    });
  }

  function recommended() {
    var set = new Set();
    qz.ans.forEach(function (list, qi) {
      (list || []).forEach(function (ai) {
        quiz.questions[qi].a[ai].mods.forEach(function (id) {
          addWithParents(set, id);
        });
      });
    });
    return set;
  }

  function saving() {
    return quiz.price > 0 && quiz.basePrice > quiz.price ? quiz.basePrice - quiz.price : 0;
  }

  function sumOf(set) {
    var sum = quiz.price;
    orderedMods(set).forEach(function (m) {
      sum += m.price;
    });
    return sum;
  }

  function quizMessage() {
    var mods = orderedMods(qz.pick);
    var t = 'Здравствуйте, ' + quiz.owner + '! Хочу забрать концепт «' + quiz.title + '»';
    if (mods.length) {
      t += ' и добавить: ' + mods.map(function (m) {
        return m.label.toLowerCase();
      }).join(', ') + '.';
    } else {
      t += ' без дополнительных модулей.';
    }
    var sum = sumOf(qz.pick);
    if (sum > 0) t += ' Итого по расчёту: ' + rub(sum) + '.';
    return t + ' Давайте обсудим.';
  }

  function optionNode(name, value, label, price, on) {
    var l = h('label', 'gdq__opt' + (on ? ' is-on' : ''));
    var inp = h('input');
    inp.type = 'checkbox';
    inp.name = name;
    inp.value = value;
    inp.checked = on;
    l.appendChild(inp);
    var mark = h('span', 'gdq__mark');
    mark.innerHTML = '<svg class="ic" aria-hidden="true" focusable="false"><use href="#i-check"/></svg>';
    l.appendChild(mark);
    l.appendChild(h('span', 'gdq__l', label));
    if (price > 0) l.appendChild(h('span', 'gdq__p', '+' + rub(price)));
    return l;
  }

  function nextLabel() {
    var last = qz.step === quiz.questions.length - 1;
    var any = (qz.ans[qz.step] || []).length > 0;
    if (!any) return last ? 'Пропустить и показать итог' : 'Пропустить';
    return last ? 'Показать итог' : 'Далее';
  }

  function renderStart() {
    var box = h('div', 'gdq__start');
    box.appendChild(h('span', 'gdq__tag', 'Ответ сразу, без указания контактов'));
    var chips = h('ul', 'gdq__chips');
    quiz.modules.forEach(function (m) {
      chips.appendChild(h('li', null, m.label));
    });
    box.appendChild(chips);
    box.appendChild(btn('btn btn--main', 'Пройти тест', 'data-q', 'start'));
    quizBox.appendChild(box);
    quizStatus.textContent = '';
  }

  function renderQuestion() {
    var total = quiz.questions.length;
    var q = quiz.questions[qz.step];
    var chosen = qz.ans[qz.step] || [];
    var bar = h('div', 'gdq__bar');
    var fill = h('i');
    fill.style.width = (qz.step / total * 100) + '%';
    bar.appendChild(fill);
    quizBox.appendChild(h('p', 'gdq__step', 'Вопрос ' + (qz.step + 1) + ' из ' + total));
    quizBox.appendChild(bar);
    var qq = h('p', 'gdq__q', q.q);
    qq.tabIndex = -1;
    qq.setAttribute('data-focus', '');
    quizBox.appendChild(qq);
    if (q.hint) quizBox.appendChild(h('p', 'gdq__hint', q.hint));
    quizBox.appendChild(h('p', 'gdq__multi', 'Можно выбрать несколько вариантов'));
    var list = h('div', 'gdq__answers');
    q.a.forEach(function (a, i) {
      list.appendChild(optionNode('ans', String(i), a.t, 0, chosen.indexOf(i) !== -1));
    });
    quizBox.appendChild(list);
    var acts = h('div', 'gdq__acts');
    var next = btn('btn btn--main', nextLabel(), 'data-q', 'next');
    next.setAttribute('data-next', '');
    acts.appendChild(next);
    if (qz.step) acts.appendChild(btn('btn btn--ghost', 'Назад', 'data-q', 'prev'));
    quizBox.appendChild(acts);
    quizStatus.textContent = 'Вопрос ' + (qz.step + 1) + ' из ' + total;
    requestAnimationFrame(function () {
      fill.style.width = ((qz.step + .5) / total * 100) + '%';
    });
  }

  function renderResult() {
    var mods = orderedMods(recommended());
    var save = saving();
    var res = h('div', 'gdq__res');
    res.appendChild(h('p', 'gdq__step', 'Ваш вариант'));
    var title = h('h3', null, 'Ваш сайт на основе концепта');
    title.tabIndex = -1;
    title.setAttribute('data-focus', '');
    res.appendChild(title);
    res.appendChild(h('p', 'gdq__sub', mods.length ? 'Отметили подходящее по вашим ответам. Снимите галочки с лишнего: стоимость пересчитается.' : 'Дополнительные модули не нужны. Их можно подключить позже, когда появится потребность.'));

    var base = h('div', 'gdq__base');
    var info = h('div', 'gdq__base-info');
    info.appendChild(h('em', null, 'Основа'));
    info.appendChild(h('b', null, 'Лендинг на основе концепта «' + quiz.title + '»'));
    info.appendChild(h('small', null, 'Главная, юридические страницы, аналитика, домен и хостинг.'));
    base.appendChild(info);
    if (quiz.price > 0) {
      var cost = h('div', 'gdq__base-cost');
      if (save > 0) {
        var old = h('s', null, rub(quiz.basePrice));
        old.setAttribute('aria-label', 'Лендинг под ключ стоит ' + rub(quiz.basePrice));
        cost.appendChild(old);
      }
      cost.appendChild(h('b', null, rub(quiz.price)));
      if (save > 0) cost.appendChild(h('span', 'gdq__save', 'Выгода ' + rub(save)));
      base.appendChild(cost);
    }
    res.appendChild(base);

    if (mods.length) {
      var opts = h('div', 'gdq__opts');
      mods.forEach(function (m) {
        opts.appendChild(optionNode('rec', m.id, m.label, m.price, qz.pick.has(m.id)));
      });
      res.appendChild(opts);
    }

    var tot = h('div', 'gdq__tot');
    tot.appendChild(h('span', null, 'Итого:'));
    var sumNode = h('b', null);
    sumNode.setAttribute('data-qsum', '');
    tot.appendChild(sumNode);
    res.appendChild(tot);
    var acts = h('div', 'gdq__acts');
    acts.appendChild(btn('btn btn--main', 'Отправить состав Анастасии', 'data-q', 'send'));
    acts.appendChild(btn('btn btn--ghost', 'Пройти ещё раз', 'data-q', 'again'));
    res.appendChild(acts);
    quizBox.appendChild(res);
    refreshTotal();
    quizStatus.textContent = mods.length ? 'Результат: ' + mods.map(function (m) {
      return m.label;
    }).join(', ') : 'Результат: модули не нужны';
  }

  function refreshTotal() {
    var node = quizBox.querySelector('[data-qsum]');
    if (!node) return;
    var sum = sumOf(qz.pick);
    node.textContent = sum > 0 ? rub(sum) : '';
    node.parentNode.hidden = sum <= 0;
  }

  function renderQuiz(focus) {
    if (!quiz) return;
    var total = quiz.questions.length;
    quizBox.textContent = '';
    quizBox.classList.toggle('is-open', qz.step >= 0);
    if (qz.step < 0) renderStart();
    else if (qz.step < total) renderQuestion();
    else renderResult();
    if (focus) {
      var f = quizBox.querySelector('[data-focus]') || quizBox.querySelector('button');
      if (f) f.focus({ preventScroll: true });
    }
  }

  function quizKeep() {
    var r = quizBox.getBoundingClientRect();
    if (r.top < 0) quizBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function quizAction(action) {
    if (action === 'start' || action === 'again') {
      qz = { step: 0, ans: [], pick: new Set() };
    } else if (action === 'prev') {
      qz.step = Math.max(0, qz.step - 1);
    } else if (action === 'next') {
      qz.step += 1;
      if (qz.step >= quiz.questions.length) qz.pick = recommended();
    } else if (action === 'send') {
      openSheet(quizMessage());
      return;
    }
    renderQuiz(true);
    quizKeep();
  }

  function onQuizChange(e) {
    var inp = e.target;
    if (!quiz || !inp.matches || !inp.matches('.gdq input')) return;
    if (inp.name === 'ans') {
      var chosen = [];
      all('input[name="ans"]', quizBox).forEach(function (n) {
        n.closest('.gdq__opt').classList.toggle('is-on', n.checked);
        if (n.checked) chosen.push(Number(n.value));
      });
      qz.ans[qz.step] = chosen;
      var next = quizBox.querySelector('[data-next]');
      if (next) next.textContent = nextLabel();
      return;
    }
    if (inp.name === 'rec') {
      if (inp.checked) addWithParents(qz.pick, inp.value);
      else {
        qz.pick.delete(inp.value);
        dropOrphans(qz.pick);
      }
      all('input[name="rec"]', quizBox).forEach(function (n) {
        n.checked = qz.pick.has(n.value);
        n.closest('.gdq__opt').classList.toggle('is-on', n.checked);
      });
      refreshTotal();
    }
  }

  function onClick(e) {
    var t = e.target;
    if (t.closest('#burger')) {
      setMenu(!menu.classList.contains('is-open'));
      return;
    }
    if (t.closest('[data-view]')) {
      setView(!mobile);
      return;
    }
    if (t.closest('#plate')) {
      setPop(pop.hidden);
      return;
    }
    var g = t.closest('[data-goto]');
    if (g) {
      var dest = find(g.getAttribute('data-goto'));
      setPop(false);
      if (dest) dest.scrollIntoView({ behavior: 'smooth', block: 'start' });
      return;
    }
    var o = t.closest('[data-open-sheet]');
    if (o) {
      e.preventDefault();
      openSheet(o.getAttribute('data-preset'));
      return;
    }
    if (t.closest('[data-close-sheet]')) {
      closeSheet();
      return;
    }
    var c = t.closest('[data-copy]');
    if (c) {
      copyText(c);
      return;
    }
    var qb = t.closest('[data-q]');
    if (qb && quiz) {
      quizAction(qb.getAttribute('data-q'));
      return;
    }
    var hb = t.closest('[data-hint]');
    if (hb) {
      closeHint();
      return;
    }
    if (menu.classList.contains('is-open') && t.closest('#menu a')) setMenu(false);
  }

  roots.forEach(function (r) {
    r.addEventListener('click', onClick);
    r.addEventListener('change', onQuizChange);
    r.addEventListener('input', function (e) {
      if (e.target.matches && e.target.matches('textarea[data-msg]')) fit(e.target);
    });
  });

  document.addEventListener('click', function (e) {
    if (pop && !pop.hidden && e.composedPath().indexOf(fab) === -1) setPop(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (sheet && sheet.classList.contains('is-open')) {
      closeSheet();
    } else if (pop && !pop.hidden) {
      setPop(false);
      plate.focus();
    } else if (menu && menu.classList.contains('is-open')) {
      setMenu(false);
      burger.focus();
    }
  });

  var views = all('[data-view]');
  var stage = null;
  var mobile = false;
  var wide = window.matchMedia('(min-width: 960px)');
  var syncFab = null;

  function barHeight() {
    var bar = document.getElementById('wpadminbar');
    return bar && getComputedStyle(bar).position === 'fixed' ? bar.offsetHeight : 0;
  }

  function sizeStage() {
    if (stage) stage.style.height = Math.max(320, window.innerHeight - barHeight()) + 'px';
  }

  function rawUrl() {
    var u = new URL(window.location.href);
    u.searchParams.set('gdd-raw', '1');
    u.hash = '';
    return u.toString();
  }

  var SKIP = { SCRIPT: 1, STYLE: 1, LINK: 1, TEMPLATE: 1, NOSCRIPT: 1, META: 1 };
  var frame = null;
  var frameLoaded = false;
  var pending = null;

  function pageRoots() {
    return Array.prototype.filter.call(document.body.children, function (n) {
      if (SKIP[n.tagName]) return false;
      var id = n.id || '';
      return id !== 'gdd-host' && id !== 'gdd-foot-host' && id !== 'gdd-stage' &&
        id !== 'wpadminbar' && id.indexOf('query-monitor') !== 0 && id.indexOf('qm') !== 0;
    });
  }

  function frameRoots(doc) {
    return Array.prototype.filter.call(doc.body.children, function (n) {
      return !SKIP[n.tagName];
    });
  }

  function flatten(roots) {
    var out = [];
    roots.forEach(function (r) {
      out.push(r);
      Array.prototype.forEach.call(r.querySelectorAll('*'), function (n) {
        if (!SKIP[n.tagName]) out.push(n);
      });
    });
    return out;
  }

  function measurable(el) {
    if (SKIP[el.tagName]) return false;
    var r = el.getBoundingClientRect();
    if (r.height <= 0 || r.width <= 0) return false;
    var pos = el.ownerDocument.defaultView.getComputedStyle(el).position;
    return pos !== 'fixed' && pos !== 'sticky' && pos !== 'absolute';
  }

  function anchorIn(roots, line) {
    var list = roots;
    var node = null;
    var frac = 0;
    while (list.length) {
      var pick = null;
      var last = null;
      for (var i = 0; i < list.length; i++) {
        if (!measurable(list[i])) continue;
        last = list[i];
        if (list[i].getBoundingClientRect().bottom > line) {
          pick = list[i];
          break;
        }
      }
      var el = pick || last;
      if (!el) break;
      var r = el.getBoundingClientRect();
      if (pick && r.top > line && node) break;
      node = el;
      frac = pick ? Math.max(0, Math.min(1, (line - r.top) / r.height)) : 1;
      if (pick && r.top > line) break;
      list = Array.prototype.slice.call(el.children);
    }
    if (!node) return null;
    return { id: node.id || '', index: flatten(roots).indexOf(node), tag: node.tagName, frac: frac };
  }

  function resolveAnchor(a, doc, roots) {
    if (a.id) {
      var byId = doc.getElementById(a.id);
      if (byId) return byId;
    }
    var n = flatten(roots)[a.index];
    return n && n.tagName === a.tag ? n : null;
  }

  function jump(win, top) {
    try {
      win.scrollTo({ top: top, behavior: 'instant' });
    } catch (_) {
      win.scrollTo(0, top);
    }
  }

  function desktopAnchor() {
    return anchorIn(pageRoots(), barHeight());
  }

  function frameAnchor() {
    try {
      var doc = frame && frameLoaded ? frame.contentDocument : null;
      return doc && doc.body ? anchorIn(frameRoots(doc), 0) : null;
    } catch (_) {
      return null;
    }
  }

  function applyToFrame(a) {
    if (!a || !frame) return;
    if (!frameLoaded) {
      pending = a;
      return;
    }
    try {
      var doc = frame.contentDocument;
      var win = frame.contentWindow;
      var el = doc && doc.body ? resolveAnchor(a, doc, frameRoots(doc)) : null;
      if (!el) return;
      var r = el.getBoundingClientRect();
      jump(win, Math.max(0, win.pageYOffset + r.top + a.frac * r.height));
    } catch (_) { }
  }

  function applyToDesktop(a) {
    if (!a) return false;
    var el = resolveAnchor(a, document, pageRoots());
    if (!el) return false;
    var r = el.getBoundingClientRect();
    jump(window, Math.max(0, window.pageYOffset + r.top + a.frac * r.height - barHeight()));
    return true;
  }

  function ensureStage() {
    if (stage) return;
    stage = document.createElement('div');
    stage.id = 'gdd-stage';
    var phone = document.createElement('div');
    phone.className = 'gdd-phone';
    frame = document.createElement('iframe');
    frame.title = 'Концепт в мобильном виде';
    frame.addEventListener('load', function () {
      frameLoaded = true;
      if (pending) {
        var a = pending;
        pending = null;
        requestAnimationFrame(function () {
          applyToFrame(a);
        });
      }
    });
    frame.src = rawUrl();
    phone.appendChild(frame);
    stage.appendChild(phone);
    if (foot && foot.parentNode) foot.parentNode.insertBefore(stage, foot);
    else document.body.appendChild(stage);
  }

  function setView(on, quiet) {
    if (on && !wide.matches) on = false;
    if (on === mobile) return;
    var anchor = null;
    if (!quiet) anchor = on ? desktopAnchor() : frameAnchor();
    mobile = on;
    if (on) ensureStage();
    html.classList.toggle('gdd-mobile', on);
    views.forEach(function (v) {
      var txt = v.querySelector('[data-view-text]');
      var icon = v.querySelector('use');
      if (txt) {
        txt.textContent = on ? 'Посмотреть десктопную версию' : 'Посмотреть мобильную версию';
      } else {
        var label = on ? 'Показать десктопную версию' : 'Показать мобильную версию';
        v.setAttribute('aria-label', label);
        v.title = label;
      }
      if (icon) icon.setAttribute('href', on ? '#i-monitor' : '#i-mobile');
    });
    setPop(false);
    if (on) sizeStage();
    if (syncFab) syncFab();
    if (quiet) return;
    if (on) {
      applyToFrame(anchor);
      var s = stage.getBoundingClientRect();
      window.scrollTo({ top: Math.max(0, s.top + window.pageYOffset - barHeight()), behavior: 'smooth' });
      return;
    }
    if (applyToDesktop(anchor)) return;
    var head = find('#header');
    if (!head) return;
    var h = head.getBoundingClientRect();
    window.scrollTo({ top: Math.max(0, h.bottom + window.pageYOffset - barHeight()), behavior: 'smooth' });
  }

  window.addEventListener('resize', function () {
    if (mobile && !wide.matches) setView(false, true);
    else sizeStage();
    if (mobile && syncFab) syncFab();
  });

  window.addEventListener('scroll', function () {
    if (mobile && syncFab) syncFab();
  }, { passive: true });

  initHint();
  renderQuiz(false);

  if (fab && 'IntersectionObserver' in window) {
    var zones = { head: false, foot: false };
    var sync = function () {
      var hide;
      if (mobile && stage) {
        var r = stage.getBoundingClientRect();
        var seen = Math.min(r.bottom, window.innerHeight) - Math.max(r.top, 0);
        hide = seen < window.innerHeight * 0.5;
      } else {
        hide = zones.head || zones.foot;
      }
      fab.classList.toggle('is-hidden', hide);
      if (hide) setPop(false);
    };
    syncFab = sync;
    var watch = function (node, key) {
      if (!node) return;
      new IntersectionObserver(function (entries) {
        zones[key] = entries[entries.length - 1].isIntersecting;
        sync();
      }).observe(node);
    };
    zones.head = true;
    watch(find('#header'), 'head');
    watch(find('#gdd-footer'), 'foot');
  } else if (fab) {
    fab.classList.remove('is-hidden');
  }

  var legalBtn = find('#legalBtn');
  var legalPop = find('#legalPop');
  if (legalBtn && legalPop) {
    var setLegal = function (on) {
      legalPop.hidden = !on;
      legalBtn.setAttribute('aria-expanded', String(on));
    };
    legalBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      setLegal(legalPop.hidden);
    });
    document.addEventListener('click', function (e) {
      var path = e.composedPath ? e.composedPath() : [];
      if (!legalPop.hidden && path.indexOf(legalPop) === -1) setLegal(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !legalPop.hidden) {
        setLegal(false);
        legalBtn.focus();
      }
    });
  }
})();
