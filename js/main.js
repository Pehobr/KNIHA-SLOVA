document.addEventListener('DOMContentLoaded', function () {
    
    // --- NOVÝ KÓD: Logika pro proklikávací boxy na archivu ---
    const storyBoxes = document.querySelectorAll('.post-type-archive-evangelijni_pribeh .entry.loop-entry');

    storyBoxes.forEach(box => {
        box.addEventListener('click', function (e) {
            // Najdeme první odkaz uvnitř boxu (obvykle odkaz v nadpisu)
            const link = box.querySelector('a');

            // Pokud odkaz existuje a neklikli jsme přímo na jiný odkaz, přesměrujeme.
            if (link && e.target.tagName !== 'A') {
                window.location.href = link.href;
            }
        });
    });


    // --- PŮVODNÍ KÓD: Přepínání záložek na detailu příběhu ---

    // Přepínání hlavních pohledů (Srovnání evangelistů / Srovnání překladů)
    const tabs = document.querySelectorAll('.view-switcher .nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    if (tabs.length > 0) {
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
    }

    // Přepínání evangelistů v záložce "Srovnání překladů"
    const evangelistTabs = document.querySelectorAll('.evangelist-switcher .nav-tab');
    const evangelistContents = document.querySelectorAll('.evangelist-translation-content');

    if (evangelistTabs.length > 0) {
        evangelistTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                evangelistTabs.forEach(t => t.classList.remove('active'));
                evangelistContents.forEach(c => c.classList.remove('active'));

                const evangelistTarget = tab.getAttribute('data-evangelist');
                const targetContent = document.getElementById('evangelist-' + evangelistTarget);

                tab.classList.add('active');
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }
});