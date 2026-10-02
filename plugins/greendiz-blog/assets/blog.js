(() => {
  const terms = document.querySelectorAll('.term[data-tip]');
  if (!terms.length) return;

  const hover = window.matchMedia('(hover: hover)');
  let tip = null;
  let current = null;

  const create = () => {
    tip = document.createElement('div');
    tip.className = 'term-tip';
    tip.id = 'term-tip';
    tip.setAttribute('role', 'tooltip');
    tip.hidden = true;
    document.body.appendChild(tip);
  };

  const place = (el) => {
    tip.hidden = false;
    tip.style.left = '0px';
    tip.style.top = '0px';
    const r = el.getBoundingClientRect();
    const w = tip.offsetWidth;
    const h = tip.offsetHeight;
    const vw = document.documentElement.clientWidth;
    let x = r.left + r.width / 2 - w / 2;
    x = Math.max(8, Math.min(x, vw - w - 8));
    let y = r.bottom + 8;
    if (y + h > window.innerHeight && r.top - h - 8 > 0) y = r.top - h - 8;
    tip.style.left = `${x + window.scrollX}px`;
    tip.style.top = `${y + window.scrollY}px`;
  };

  const show = (el) => {
    if (!tip) create();
    if (current && current !== el) current.setAttribute('aria-expanded', 'false');
    current = el;
    tip.textContent = el.dataset.tip;
    el.setAttribute('aria-describedby', 'term-tip');
    el.setAttribute('aria-expanded', 'true');
    place(el);
  };

  const hide = () => {
    if (!tip || !current) return;
    tip.hidden = true;
    current.removeAttribute('aria-describedby');
    current.setAttribute('aria-expanded', 'false');
    current = null;
  };

  terms.forEach((el) => {
    el.tabIndex = 0;
    el.setAttribute('role', 'button');
    el.setAttribute('aria-expanded', 'false');

    el.addEventListener('click', (e) => {
      e.stopPropagation();
      if (current === el && !hover.matches) hide();
      else show(el);
    });

    el.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        current === el ? hide() : show(el);
      }
    });

    el.addEventListener('mouseenter', () => {
      if (hover.matches) show(el);
    });

    el.addEventListener('mouseleave', () => {
      if (hover.matches) hide();
    });

    el.addEventListener('blur', () => {
      if (current === el) hide();
    });
  });

  document.addEventListener('click', hide);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hide();
  });
  window.addEventListener('resize', hide);
  window.addEventListener('scroll', () => {
    if (current) place(current);
  }, { passive: true });
})();
