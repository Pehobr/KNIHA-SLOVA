document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.view-switcher .nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Odebrání aktivní třídy ze všech tlačítek a obsahů
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Přidání aktivní třídy na kliknuté tlačítko a jeho cíl
            const targetId = tab.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);

            tab.classList.add('active');
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
});