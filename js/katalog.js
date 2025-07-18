document.addEventListener('DOMContentLoaded', function () {
    const viewTabs = document.querySelectorAll('.view-switcher .nav-tab');
    const viewContents = document.querySelectorAll('.tab-content');
    const evangelistTabs = document.querySelectorAll('.evangelist-switcher .nav-tab');
    const evangelistContents = document.querySelectorAll('.evangelist-citation-content');

    viewTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            viewTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            viewContents.forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tab.dataset.target).classList.add('active');
        });
    });

    evangelistTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            evangelistTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            evangelistContents.forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById('evangelist-' + tab.dataset.evangelist).classList.add('active');
        });
    });
});