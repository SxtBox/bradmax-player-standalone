(function () {
    'use strict';

    var form = document.getElementById('stream-form');
    var input = document.getElementById('stream-url');
    var video = document.getElementById('stream-video');
    var playerContainer = document.getElementById('stream-player');
    var clearButton = document.getElementById('stream-clear');
    var status = document.getElementById('stream-status');
    var tabButtons = document.querySelectorAll('[data-tab-target]');
    var customPlayer = null;

    if (!form || !input || !video || !playerContainer || !clearButton || !status) {
        return;
    }

    function setStatus(message, type) {
        status.textContent = message;
        status.className = 'stream-status' + (type ? ' stream-status-' + type : '');
    }

    function clearPlayer() {
        if (customPlayer && window.bradmax && window.bradmax.player) {
            window.bradmax.player.destroy(customPlayer);
            customPlayer = null;
        }
        video.pause();
        video.removeAttribute('src');
        video.load();
        playerContainer.classList.remove('is-visible');
        playerContainer.innerHTML = '<video id="stream-video" controls playsinline preload="metadata">Your browser does not support HTML5 video.</video>';
        video = document.getElementById('stream-video');
        watchVideoErrors();
        input.value = '';
        setStatus('', '');
    }

    function watchVideoErrors() {
        video.addEventListener('error', function () {
            setStatus('The stream could not be played. Check the URL, CORS policy, and browser format support.', 'error');
        });
    }

    function createBradmaxPlayer(streamUrl) {
        if (playerContainer.getAttribute('data-bradmax-enabled') !== 'true' ||
            !window.bradmax || !window.bradmax.player || typeof window.bradmax.player.create !== 'function') {
            return false;
        }

        playerContainer.innerHTML = '';
        customPlayer = window.bradmax.player.create(playerContainer, {
            dataProvider: {
                title: 'Stream',
                source: [{ url: streamUrl }]
            }
        });
        playerContainer.classList.add('is-visible');
        return true;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        var streamUrl = input.value.trim();
        if (!streamUrl) {
            setStatus('Enter a stream URL first.', 'error');
            input.focus();
            return;
        }

        if (!/^https?:\/\//i.test(streamUrl)) {
            setStatus('Only http:// and https:// stream URLs are allowed.', 'error');
            input.focus();
            return;
        }

        if (createBradmaxPlayer(streamUrl)) {
            setStatus('Bradmax player loaded. Press play if it does not start automatically.', 'success');
            return;
        }

        video.pause();
        video.src = streamUrl;
        video.load();
        playerContainer.classList.add('is-visible');
        setStatus('HTML5 player loaded. Press play if it does not start automatically.', 'success');

        var playRequest = video.play();
        if (playRequest && typeof playRequest.catch === 'function') {
            playRequest.catch(function () {
                setStatus('Stream loaded. Press play to start playback.', 'success');
            });
        }
    });

    clearButton.addEventListener('click', clearPlayer);
    watchVideoErrors();

    Array.prototype.forEach.call(tabButtons, function (button) {
        button.addEventListener('click', function () {
            var targetId = button.getAttribute('data-tab-target');
            Array.prototype.forEach.call(tabButtons, function (tabButton) {
                tabButton.classList.toggle('is-active', tabButton === button);
            });
            Array.prototype.forEach.call(document.querySelectorAll('.tab-panel'), function (panel) {
                panel.classList.toggle('is-active', panel.id === targetId);
            });
        });
    });
}());
