const nav = document.querySelector('.landing__nav');
const menuButton = document.querySelector('.landing__menu');
if (nav && menuButton) {
  menuButton.addEventListener('click', () => {
    nav.classList.toggle('is-open');
    const expanded = menuButton.getAttribute('aria-expanded') === 'true';
    menuButton.setAttribute('aria-expanded', (!expanded).toString());
    menuButton.setAttribute(
      'aria-label',
      !expanded ? 'إغلاق قائمة التنقل' : 'فتح قائمة التنقل'
    );
  });
}

const sidebar = document.querySelector('.sidebar');
const sidebarToggle = document.querySelector('.sidebar__toggle');
if (sidebar && sidebarToggle) {
  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('is-open');
    const expanded = sidebarToggle.getAttribute('aria-expanded') === 'true';
    sidebarToggle.setAttribute('aria-expanded', (!expanded).toString());
  });
}
