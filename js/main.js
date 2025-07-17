document.addEventListener('DOMContentLoaded', function () {
    
    // Logika pro proklikávací boxy na archivu
    const storyBoxes = document.querySelectorAll(
        '.post-type-archive-evangelijni_pribeh .entry.loop-entry, .page-template-template-pribehy-php .entry.loop-entry'
    );
    if (storyBoxes.length > 0) {
        storyBoxes.forEach(box => {
            box.style.cursor = 'pointer'; 
            box.addEventListener('click', function (e) {
                const link = box.querySelector('a');
                if (link && e.target.tagName !== 'A') {
                    window.location.href = link.href;
                }
            });
        });
    }

    // Přepínání hlavních záložek
    const viewTabs = document.querySelectorAll('.view-switcher .nav-tab');
    const viewContents = document.querySelectorAll('.tab-content');
    if (viewTabs.length > 0) {
        viewTabs.forEach(tab => {
            tab.addEventListener('click', () => {
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

    // Funkce pro obsluhu pod-záložek evangelistů
    function setupEvangelistSwitcher(switcherClass, contentClass, dataAttribute) {
        const tabs = document.querySelectorAll(`.${switcherClass} .nav-tab`);
        const contents = document.querySelectorAll(`.${contentClass}`);
        if (tabs.length > 0) {
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    const targetId = tab.getAttribute(dataAttribute);
                    const targetContent = document.getElementById(contentClass.split(' ')[0] + '-' + targetId);
                    tab.classList.add('active');
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
        }
    }

    // Aktivace přepínačů pro jednotlivé sekce
    setupEvangelistSwitcher('evangelist-switcher', 'evangelist-translation-content', 'data-evangelist');
    setupEvangelistSwitcher('exegesis-switcher', 'exegesis-content', 'data-exegesis-target');
    setupEvangelistSwitcher('spiritual-switcher', 'spiritual-content', 'data-spiritual-target');

});