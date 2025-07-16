document.addEventListener('DOMContentLoaded', function () {
    // Původní kód pro přepínání hlavních pohledů (Srovnání evangelistů / Srovnání překladů)
    const tabs = document.querySelectorAll('.view-switcher .nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            const targetId = tab.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);

            tab.classList.add('active');
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });

    // Nový kód pro přepínání evangelistů v záložce "Srovnání překladů"
    const evangelistTabs = document.querySelectorAll('.evangelist-switcher .nav-tab');
    const evangelistContents = document.querySelectorAll('.evangelist-translation-content');

    evangelistTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Skryjeme všechny obsahy a deaktivujeme všechna tlačítka evangelistů
            evangelistTabs.forEach(t => t.classList.remove('active'));
            evangelistContents.forEach(c => c.classList.remove('active'));

            // Zobrazíme cílový obsah a aktivujeme kliknuté tlačítko
            const evangelistTarget = tab.getAttribute('data-evangelist');
            const targetContent = document.getElementById('evangelist-' + evangelistTarget);

            tab.classList.add('active');
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
});