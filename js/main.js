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
    // Tento kód je nyní obecnější a bude fungovat na více místech.
    
    // Přepínání hlavních pohledů (Detail příběhu, Katalog)
    const viewTabs = document.querySelectorAll('.view-switcher .nav-tab');
    const viewContents = document.querySelectorAll('.tab-content');

    if (viewTabs.length > 0) {
        viewTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Skryjeme vše ve stejné skupině
                viewTabs.forEach(t => t.classList.remove('active'));
                viewContents.forEach(c => c.classList.remove('active'));

                const targetId = tab.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);

                tab.classList.add('active');
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }

    // Přepínání evangelistů (Detail příběhu, Katalog)
    const evangelistTabs = document.querySelectorAll('.evangelist-switcher .nav-tab');
    const evangelistContents = document.querySelectorAll('.evangelist-translation-content, .evangelist-citation-content');

    if (evangelistTabs.length > 0) {
        // Nejprve skryjeme všechny obsahy kromě prvního aktivního
        let isFirstActiveFound = false;
        evangelistContents.forEach(c => {
            if (c.classList.contains('active')) {
                if (isFirstActiveFound) {
                    c.classList.remove('active');
                }
                isFirstActiveFound = true;
            }
        });
        
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