document.addEventListener('DOMContentLoaded', function () {

    /**
     * ========================================================================
     * I. VYTVOŘENÍ VLASTNÍ LEVÉ IKONY V HLAVIČCE
     * Tato část byla omylem odstraněna a nyní je vrácena zpět.
     * Zajišťuje, že se levá ikona vždy zobrazí.
     * ========================================================================
     */
    function createLeftMenuIcon() {
        const headerLeftSection = document.querySelector('#mobile-header .site-header-main-section-left');

        // Pokud na stránce neexistuje levý kontejner v hlavičce, nic neděláme.
        if (!headerLeftSection) {
            return;
        }

        // Zkontrolujeme, jestli už ikona nebyla vytvořena, abychom ji neduplikovali.
        if (!headerLeftSection.querySelector('.custom-left-menu-icon')) {
            const leftMenuButton = document.createElement('button');
            leftMenuButton.className = 'custom-left-menu-icon';
            leftMenuButton.setAttribute('aria-label', 'Otevřít levé menu');
            leftMenuButton.setAttribute('aria-expanded', 'false');
            leftMenuButton.innerHTML = `
                <span class="kadence-svg-iconset">
                    <svg aria-hidden="true" class="kadence-svg-icon kadence-menu-svg" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <title>Přepínání levé nabídky</title>
                        <path d="M3 13h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 7h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 19h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1z"></path>
                    </svg>
                </span>
            `;
            // Vložíme ikonu do levé části hlavičky
            headerLeftSection.appendChild(leftMenuButton);
        }
    }


    /**
     * ========================================================================
     * II. FUNKCE PRO OVLÁDÁNÍ VYSOUVACÍCH MENU
     * Univerzální funkce pro levé i pravé menu.
     * ========================================================================
     */
    function setupDrawerMenu(options) {
        // Čekáme, až se DOM plně načte, abychom měli jistotu, že ikony existují
        // Zvláště levá, která je vytvářena dynamicky
        const openButton = document.querySelector(options.openSelector);
        const drawer = document.getElementById(options.drawerId);

        if (!openButton || !drawer) {
            return;
        }

        const closeButton = drawer.querySelector(options.closeSelector);
        const overlay = drawer.querySelector(options.overlaySelector);
        const body = document.body;

        const openMenu = (e) => {
            if (options.preventDefault) {
                e.preventDefault();
                e.stopPropagation();
            }
            body.classList.add(options.bodyClass);
            openButton.setAttribute('aria-expanded', 'true');
        };

        const closeMenu = () => {
            body.classList.remove(options.bodyClass);
            openButton.setAttribute('aria-expanded', 'false');
        };

        openButton.addEventListener('click', openMenu);
        closeButton.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && body.classList.contains(options.bodyClass)) {
                closeMenu();
            }
        });
    }

    /**
     * ========================================================================
     * III. PŮVODNÍ FUNKCIONALITA STRÁNKY (ZACHOVÁNO)
     * ========================================================================
     */

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
                    const idPrefix = switcherClass.split('-')[0];
                    const targetContent = document.getElementById(idPrefix + '-' + targetId);
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

    // Funkce pro vytvoření a ovládání audio přehrávače
    function setupPodcastPlayer() {
        const playerWrapper = document.querySelector('.podcast-player-container');
        if (!playerWrapper) return;

        const sourceContent = playerWrapper.querySelector('.podcast-source-content');
        const mp3Link = sourceContent ? sourceContent.querySelector('a[href$=".mp3"]') : null;

        if (!mp3Link) {
            playerWrapper.innerHTML = '<p>Pro tento příběh zatím není k dispozici žádný podcast.</p>';
            return;
        }

        const mp3Url = mp3Link.href;
        playerWrapper.innerHTML = `<div class="ai-audio-player"><button class="play-pause-btn" aria-label="Přehrát podcast"><i class="fa fa-play"></i></button><div class="progress-bar-wrapper"><div class="progress-bar"></div></div><div class="time-display">0:00</div></div>`;

        const audio = new Audio(mp3Url);
        const playPauseBtn = playerWrapper.querySelector('.play-pause-btn');
        const playIcon = playPauseBtn.querySelector('i');
        const progressBar = playerWrapper.querySelector('.progress-bar');
        const progressBarWrapper = playerWrapper.querySelector('.progress-bar-wrapper');
        const timeDisplay = playerWrapper.querySelector('.time-display');

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }

        playPauseBtn.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                playIcon.className = 'fa fa-pause';
                playPauseBtn.setAttribute('aria-label', 'Pozastavit podcast');
            } else {
                audio.pause();
                playIcon.className = 'fa fa-play';
                playPauseBtn.setAttribute('aria-label', 'Přehrát podcast');
            }
        });

        audio.addEventListener('timeupdate', () => {
            const progressPercent = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
            timeDisplay.textContent = formatTime(audio.currentTime);
        });

        progressBarWrapper.addEventListener('click', (e) => {
            const wrapperWidth = progressBarWrapper.clientWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;
            if (duration) {
                audio.currentTime = (clickX / wrapperWidth) * duration;
            }
        });
    }

    /**
     * ========================================================================
     * IV. SPOUŠTĚNÍ VŠECH FUNKCÍ
     * ========================================================================
     */

    // 1. Vytvoříme ikonu pro levé menu
    createLeftMenuIcon();
    
    // 2. Aktivujeme ovládání pro obě menu
    setupDrawerMenu({
        openSelector: '.custom-left-menu-icon',
        drawerId: 'left-mobile-drawer',
        closeSelector: '.drawer-toggle-left',
        overlaySelector: '.drawer-overlay-left',
        bodyClass: 'left-menu-is-open',
        preventDefault: false
    });
    setupDrawerMenu({
        openSelector: '#mobile-toggle',
        drawerId: 'right-mobile-drawer',
        closeSelector: '.drawer-toggle-right',
        overlaySelector: '.drawer-overlay-right',
        bodyClass: 'right-menu-is-open',
        preventDefault: true
    });

    // 3. Aktivujeme audio přehrávač
    setupPodcastPlayer();

});