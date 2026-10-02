(function () {
  'use strict';

  var GD = window.GD = window.GD || {};
  var sheetEl = document.getElementById('sheet');
  GD.owner = (sheetEl && sheetEl.getAttribute('data-owner')) || '';

  var $ = function (s, r) {
    return (r || document).querySelector(s);
  };
  var $$ = function (s, r) {
    return Array.prototype.slice.call((r || document).querySelectorAll(s));
  };
  var MONTHS = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
  function targetMonth() {
    var d = new Date()
      , last = new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
    return MONTHS[last - d.getDate() <= 7 ? (d.getMonth() + 1) % 12 : d.getMonth()];
  }
  function updateMonthBadges() {
    $$('[data-month]').forEach(function (e) {
      e.textContent = targetMonth();
    });
  }
  var yearEl = $('#year');
  if (yearEl)
    yearEl.textContent = new Date().getFullYear();
  updateMonthBadges();

  var root = document.documentElement
    , themeBtn = $('#themeBtn')
    , themeMeta = $('meta[name="theme-color"]')
    , darkMq = window.matchMedia('(prefers-color-scheme: dark)');
  function setTheme(t, save) {
    root.setAttribute('data-theme', t);
    if (themeBtn) {
      themeBtn.setAttribute('aria-pressed', String(t === 'dark'));
      themeBtn.setAttribute('aria-label', t === 'dark' ? 'Светлая тема' : 'Тёмная тема');
    }
    if (themeMeta)
      themeMeta.setAttribute('content', t === 'dark' ? '#141517' : '#FBFAF9');
    if (save)
      try {
        localStorage.setItem('gd_theme', t);
      } catch (_) { }
  }
  setTheme(root.getAttribute('data-theme'));
  if (themeBtn)
    themeBtn.addEventListener('click', function () {
      setTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark', true);
    });
  var onScheme = function (e) {
    var saved = null;
    try {
      saved = localStorage.getItem('gd_theme');
    } catch (_) { }
    if (!saved)
      setTheme(e.matches ? 'dark' : 'light');
  };
  if (darkMq.addEventListener)
    darkMq.addEventListener('change', onScheme);
  else if (darkMq.addListener)
    darkMq.addListener(onScheme);

  var burger = $('#burger')
    , menu = $('#menu');
  function setMenu(open) {
    if (!burger || !menu)
      return;
    menu.classList.toggle('is-open', open);
    burger.setAttribute('aria-expanded', open);
    burger.setAttribute('aria-label', open ? 'Закрыть меню' : 'Меню');
    document.body.classList.toggle('menu-lock', open);
  }
  if (burger && menu) {
    burger.addEventListener('click', function (e) {
      e.stopPropagation();
      setMenu(!menu.classList.contains('is-open'));
    });
    menu.addEventListener('click', function (e) {
      if (e.target.closest('a'))
        setMenu(false);
    });
  }
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.top'))
      setMenu(false);
  });

  var header = $('#header');
  function onScroll() {
    if (header)
      header.classList.toggle('is-scrolled', window.scrollY > 8);
  }
  window.addEventListener('scroll', onScroll, {
    passive: true
  });
  onScroll();

  function fit(t) {
    t.style.height = 'auto';
    if (t.scrollHeight)
      t.style.height = t.scrollHeight + 'px';
  }
  document.addEventListener('input', function (e) {
    if (e.target.matches && e.target.matches('textarea[data-msg]'))
      fit(e.target);
  });
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-copy]');
    if (!b)
      return;
    var text = $('[data-msg]', b.closest('.cblock')).value
      , label = $('span', b)
      , use = $('use', b);
    function done(ok) {
      label.textContent = ok ? 'Скопировано' : 'Выделите и скопируйте';
      if (ok)
        use.setAttribute('href', '#i-check');
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
    if (navigator.clipboard && window.isSecureContext)
      navigator.clipboard.writeText(text).then(function () {
        done(true);
      }, fallback);
    else
      fallback();
  });

  var openEl = null
    , lastFocus = null;
  function openModal(id, msg) {
    var prev = openEl;
    if (openEl)
      closeModal(true);
    var m = document.getElementById(id)
      , panel = m && $('.sheet__panel', m);
    if (!m || !panel)
      return;
    if (!prev)
      lastFocus = document.activeElement;
    if (id === 'sheet')
      $$('[data-msg]', m).forEach(function (t) {
        t.value = msg || defaultMessage();
      });
    setMenu(false);
    m.classList.add('is-open');
    m.setAttribute('aria-hidden', 'false');
    document.body.classList.add('lock');
    openEl = m;
    layout();
    setTimeout(function () {
      panel.scrollTop = 0;
      panel.focus({
        preventScroll: true
      });
      $$('[data-msg]', panel).forEach(fit);
    }, 60);
  }
  function closeModal(silent) {
    if (!openEl)
      return;
    openEl.classList.remove('is-open');
    openEl.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('lock');
    openEl = null;
    layout();
    if (!silent && lastFocus && lastFocus.focus)
      lastFocus.focus({
        preventScroll: true
      });
  }
  document.addEventListener('click', function (e) {
    var o = e.target.closest('[data-open-sheet]');
    if (o) {
      e.preventDefault();
      openModal('sheet', o.getAttribute('data-preset'));
      return;
    }
    if (e.target.closest('[data-close-sheet]'))
      closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (openEl)
        closeModal();
      else if (menu && menu.classList.contains('is-open')) {
        setMenu(false);
        if (burger)
          burger.focus();
      }
    }
    if (e.key === 'Tab' && openEl) {
      var panel = $('.sheet__panel', openEl);
      var f = $$('a[href],button:not([disabled]),input:not([disabled]),textarea', panel).filter(function (n) {
        return n.offsetParent !== null || n.type === 'radio' || n.type === 'checkbox';
      });
      if (!f.length)
        return;
      var a = f[0]
        , z = f[f.length - 1];
      if (e.shiftKey && (document.activeElement === a || document.activeElement === panel)) {
        e.preventDefault();
        z.focus();
      } else if (!e.shiftKey && document.activeElement === z) {
        e.preventDefault();
        a.focus();
      }
    }
  });

  function layout() {
    document.dispatchEvent(new CustomEvent('gd:layout'));
  }
  function defaultMessage() {
    return GD.defaultMessage ? GD.defaultMessage() : 'Здравствуйте, ' + GD.owner + '! Мне нужен сайт, давайте обсудим.';
  }
  GD.fit = fit;
  GD.openSheet = function (msg) {
    openModal('sheet', msg);
  };

  var cookie = $('#cookie')
    , cookieSeen = false;
  if (cookie) {
    try {
      cookieSeen = !!localStorage.getItem('gd_cookie');
    } catch (_) { }
    if (!cookieSeen)
      cookie.hidden = false;
    cookie.addEventListener('click', function (e) {
      var b = e.target.closest('[data-cookie]');
      if (!b)
        return;
      try {
        localStorage.setItem('gd_cookie', b.dataset.cookie);
      } catch (_) { }
      cookie.hidden = true;
      layout();
    });
  }

  var legalBtn = $('#legalBtn')
    , legalPop = $('#legalPop');
  if (legalBtn) {
    var setLegal = function (on) {
      legalPop.hidden = !on;
      legalBtn.setAttribute('aria-expanded', String(on));
    };
    legalBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      setLegal(legalPop.hidden);
    });
    document.addEventListener('click', function (e) {
      if (!legalPop.hidden && !e.target.closest('#legalPop'))
        setLegal(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !legalPop.hidden) {
        setLegal(false);
        legalBtn.focus();
      }
    });
  }
})();
