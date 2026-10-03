(() => {
  const root = document.documentElement;
  const savedTheme = localStorage.getItem('theme') || localStorage.getItem('imwasim-theme');
  if (savedTheme === 'light' || savedTheme === 'dark') root.dataset.theme = savedTheme;

  document.querySelector('.theme-toggle')?.addEventListener('click', () => {
    root.dataset.theme = root.dataset.theme === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', root.dataset.theme);
    localStorage.removeItem('imwasim-theme');
  });

  const menuButton = document.querySelector('.menu-toggle');
  const menu = document.querySelector('#site-menu');
  menuButton?.addEventListener('click', () => {
    const open = menu?.classList.toggle('is-open') ?? false;
    menuButton.setAttribute('aria-expanded', String(open));
  });

  const progress = document.querySelector('.reading-progress');
  const updateProgress = () => {
    if (!progress) return;
    const available = document.documentElement.scrollHeight - innerHeight;
    progress.style.width = `${available > 0 ? Math.min(100, scrollY / available * 100) : 0}%`;
  };
  addEventListener('scroll', updateProgress, { passive: true });
  updateProgress();

  const architecture = document.querySelector('[data-interactive="architecture"]');
  const descriptions = {
    host: 'The host owns the user experience and controls permissions, context, and consent.',
    client: 'The client maintains the protocol connection and exchanges structured messages with one server.',
    server: 'The server publishes focused capabilities while protecting the underlying systems and data.'
  };
  architecture?.querySelectorAll('[data-layer]').forEach((button) => {
    button.addEventListener('click', () => {
      architecture.querySelectorAll('[data-layer]').forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
      architecture.querySelectorAll('[data-node]').forEach((node) => node.querySelector('.node')?.classList.toggle('is-active', node.dataset.node === button.dataset.layer));
      architecture.querySelector('.figure-note').textContent = descriptions[button.dataset.layer];
    });
  });

  document.querySelectorAll('[data-interactive="primitives"] .primitive').forEach((button) => {
    button.addEventListener('click', () => {
      const figure = button.closest('.interactive-figure');
      figure.querySelectorAll('.primitive').forEach((item) => item.classList.toggle('is-active', item === button));
      figure.querySelector('.figure-note').textContent = button.dataset.detail;
    });
  });
})();
