(function () {
  'use strict';
  var nav = document.querySelector('[data-pfilter]');
  var grid = document.querySelector('[data-pgrid]');
  if (!nav || !grid)
    return;
  var buttons = Array.prototype.slice.call(nav.querySelectorAll('[data-filter]'));
  var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-type]'));

  function apply(type) {
    cards.forEach(function (card) {
      card.hidden = type !== '' && card.getAttribute('data-type') !== type;
    });
    buttons.forEach(function (btn) {
      if (btn.getAttribute('data-filter') === type)
        btn.setAttribute('aria-current', 'true');
      else
        btn.removeAttribute('aria-current');
    });
  }

  nav.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-filter]');
    if (!btn)
      return;
    e.preventDefault();
    apply(btn.getAttribute('data-filter'));
    if (window.history && history.replaceState)
      history.replaceState(null, '', btn.getAttribute('href'));
  });
})();
