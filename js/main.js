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
                    
                    // --- TOTO JE OPRAVA ---
                    // Předpona pro ID (např. "exegesis", "spiritual") se odvodí z názvu třídy přepínače.
                    const idPrefix = switcherClass.split('-')[0];
                    const targetContent = document.getElementById(idPrefix + '-' + targetId);
                    // --- KONEC OPRAVY ---

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

document.addEventListener('DOMContentLoaded', function () {
    
    // ... (stávající kód pro proklikávací boxy a přepínání záložek) ...

    // --- PŘIDÁNO: Funkce pro vytvoření a ovládání audio přehrávače ---
    function setupPodcastPlayer() {
        const playerWrapper = document.querySelector('.podcast-player-container');
        if (!playerWrapper) return;

        // Najdeme skrytý obsah a v něm odkaz na MP3
        const sourceContent = playerWrapper.querySelector('.podcast-source-content');
        const mp3Link = sourceContent ? sourceContent.querySelector('a[href$=".mp3"]') : null;

        if (!mp3Link) {
            playerWrapper.innerHTML = '<p>Pro tento příběh zatím není k dispozici žádný podcast.</p>';
            return;
        }

        const mp3Url = mp3Link.href;
        
        // Vytvoříme HTML strukturu přehrávače
        playerWrapper.innerHTML = `
            <div class="ai-audio-player">
                <button class="play-pause-btn" aria-label="Přehrát podcast">
                    <i class="fa fa-play"></i>
                </button>
                <div class="progress-bar-wrapper">
                    <div class="progress-bar"></div>
                </div>
                <div class="time-display">0:00</div>
            </div>
        `;

        const audio = new Audio(mp3Url);
        const playPauseBtn = playerWrapper.querySelector('.play-pause-btn');
        const playIcon = playPauseBtn.querySelector('i');
        const progressBar = playerWrapper.querySelector('.progress-bar');
        const progressBarWrapper = playerWrapper.querySelector('.progress-bar-wrapper');
        const timeDisplay = playerWrapper.querySelector('.time-display');

        // Formátování času z sekund na MM:SS
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }

        // Přepínání play/pause
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

        // Aktualizace progress baru a času
        audio.addEventListener('timeupdate', () => {
            const progressPercent = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
            timeDisplay.textContent = formatTime(audio.currentTime);
        });
        
        // Posouvání v nahrávce kliknutím na progress bar
        progressBarWrapper.addEventListener('click', (e) => {
            const wrapperWidth = progressBarWrapper.clientWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;
            if (duration) {
                audio.currentTime = (clickX / wrapperWidth) * duration;
            }
        });
    }

    // Aktivace všech funkcí
    setupPodcastPlayer();
    
});