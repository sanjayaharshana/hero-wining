<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Winner Draw - Hero | Abans Auto Grand Draw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #0a0000;
            color: #fff;
            overflow: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100dvh;
            cursor: pointer;
        }

        .bg-video {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .board {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 92%;
            max-width: 720px;
            min-height: min(60vh, 420px);
            background: #000;
            border: 6px solid #161616;
            border-radius: 14px;
            padding: 40px 24px;
            overflow: hidden;
            box-shadow: inset 0 0 50px rgba(255, 0, 0, 0.18), 0 0 30px rgba(255, 0, 0, 0.2);
        }

        .board::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1.5px);
            background-size: 7px 7px;
            pointer-events: none;
        }

        .board-logo {
            position: relative;
            display: block;
            width: 60%;
            max-width: 320px;
            margin: 0 auto;
            animation: logoFloat 2.6s ease-in-out infinite;
            transition: opacity 0.45s ease, transform 0.45s ease;
        }

        .board-logo.logo-hidden {
            opacity: 0;
            transform: scale(0.4) rotate(6deg);
            pointer-events: none;
        }

        .board-logo.logo-removed {
            display: none;
        }

        .led-text {
            display: none;
            position: relative;
            font-family: 'VT323', 'Courier New', monospace;
            color: #ff2b2b;
            text-shadow: 0 0 6px #ff0000, 0 0 18px #ff0000cc;
            font-size: clamp(30px, 8vw, 64px);
            text-align: center;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            line-height: 1.3em;
            word-break: break-word;
        }

        .led-text.visible {
            display: block;
        }

        .led-text.spinning {
            animation: flicker 0.12s infinite;
        }

        .led-text.reveal {
            animation: revealPop 0.55s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .led-sub {
            display: none;
            position: relative;
            font-family: 'VT323', 'Courier New', monospace;
            color: #ffb020;
            text-shadow: 0 0 6px #ff8800aa;
            text-align: center;
            font-size: clamp(16px, 4.5vw, 28px);
            letter-spacing: 0.1em;
            margin-top: 8px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .led-sub.visible {
            display: block;
            opacity: 1;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; }
            45% { opacity: 0.85; }
            50% { opacity: 0.55; }
            55% { opacity: 0.9; }
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0) scale(1); filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.45)); }
            50% { transform: translateY(-10px) scale(1.03); filter: drop-shadow(0 0 22px rgba(255, 0, 0, 0.75)); }
        }

        @keyframes revealPop {
            0% { opacity: 0; transform: scale(0.6); }
            60% { opacity: 1; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <video class="bg-video" src="{{ asset('background_board.mp4') }}" autoplay muted loop playsinline></video>

    <div class="board">
        <img class="board-logo" id="board-logo" src="{{ asset('raffle_draw/02/Logo_02.png') }}" alt="">
        <div class="led-text" id="led-text"></div>
        <div class="led-sub" id="led-sub">&nbsp;</div>
    </div>

    <script>
        (function () {
            var pool = @json($pool->pluck('name')->values());
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var boardLogo = document.getElementById('board-logo');
            var ledText = document.getElementById('led-text');
            var ledSub = document.getElementById('led-sub');
            var drawing = false;
            var spinTimer = null;

            function randomFrom(list) {
                return list[Math.floor(Math.random() * list.length)];
            }

            function draw() {
                if (drawing || pool.length === 0) {
                    return;
                }

                drawing = true;
                boardLogo.classList.add('logo-hidden');
                boardLogo.addEventListener('transitionend', function onLogoHidden() {
                    boardLogo.classList.add('logo-removed');
                    boardLogo.removeEventListener('transitionend', onLogoHidden);
                });
                ledText.classList.remove('reveal');
                ledText.classList.add('visible', 'spinning');
                ledSub.classList.add('visible');
                ledSub.textContent = ' ';

                spinTimer = setInterval(function () {
                    ledText.textContent = randomFrom(pool);
                }, 80);

                var minDelay = new Promise(function (resolve) {
                    setTimeout(resolve, 2200);
                });

                var request = fetch('{{ route('admin.winner-draw.draw') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                });

                Promise.all([request, minDelay]).then(function (results) {
                    var result = results[0];
                    clearInterval(spinTimer);
                    ledText.classList.remove('spinning');

                    if (!result.ok) {
                        ledText.textContent = 'No Entries Left';
                        ledSub.textContent = result.data.message || '';
                        drawing = false;
                        return;
                    }

                    var winner = result.data;

                    ledText.textContent = winner.name;
                    ledText.classList.add('reveal');
                    ledSub.textContent = '';
                    ledSub.classList.remove('visible');

                    pool = pool.filter(function (name) {
                        return name !== winner.name;
                    });

                    drawing = false;
                }).catch(function () {
                    clearInterval(spinTimer);
                    ledText.classList.remove('spinning');
                    ledText.textContent = 'Something Went Wrong';
                    ledSub.textContent = 'Please try again.';
                    drawing = false;
                });
            }

            function toggleFullscreen() {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    document.documentElement.requestFullscreen().catch(function () {});
                }
            }

            document.body.addEventListener('click', draw);
            document.body.addEventListener('dblclick', toggleFullscreen);
            document.addEventListener('keydown', function (event) {
                if (event.key === ' ' || event.key === 'Enter') {
                    event.preventDefault();
                    draw();
                }
            });
        })();
    </script>
</body>
</html>
