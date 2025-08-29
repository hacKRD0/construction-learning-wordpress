
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.site-navigation-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (toggleBtn && mobileMenu) {
        toggleBtn.addEventListener('click', function () {
            const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            toggleBtn.setAttribute('aria-expanded', String(!isExpanded));
            mobileMenu.hidden = isExpanded;
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !mobileMenu.hidden) {
                mobileMenu.hidden = true;
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
