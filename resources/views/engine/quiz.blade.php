<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Flora Margarine - Stall {{ $stall }} Quiz</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .raffle-shell {
            --card-w: min(100vw, 480px);
            width: 100%;
            margin: 0;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }

        .screen {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100dvh;
            width: 100%;
            padding: 6vh 0 5vh;
            overflow-y: auto;
            box-sizing: border-box;
            background: url("{{ asset('BG.jpg.png') }}") center / cover no-repeat;
        }

        .screen.active {
            display: flex;
        }

        .quiz-logo {
            width: 60%;
            max-width: 260px;
            margin-bottom: 2vh;
        }

        .stall-badge {
            display: none;
        }

        .quiz-heading {
            width: 92%;
            font-size: calc(var(--card-w) * 0.08);
            font-weight: bold;
            color: #009a4c;
            text-align: center;
            margin: 0;
        }

        .quiz-subtext {
            color: #666666;
            font-size: calc(var(--card-w) * 0.036);
            margin-top: 1vh;
            margin-bottom: 3vh;
            text-align: center;
        }

        .quiz-options {
            width: 100%;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: stretch;
            justify-content: center;
            gap: 2%;
            padding: 1vh 2% 1.5vh;
        }

        .quiz-option {
            position: relative;
            flex: 1 1 0;
            min-width: 0;
            min-height: calc(var(--card-w) * 0.75);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            text-align: center;
            padding: calc(var(--card-w) * 0.06) calc(var(--card-w) * 0.012);
            border: 2px solid #cccccc;
            border-radius: 12px;
            background-color: #2b2b2b;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            color: #ffffff;
            font-size: calc(var(--card-w) * 0.054);
            font-weight: bold;
            line-height: 1.2;
            cursor: pointer;
        }

        .quiz-option-text {
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.85);
        }

        .quiz-option-shine {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            overflow: hidden;
            pointer-events: none;
        }

        .quiz-option-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -60%;
            width: 45%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.75), transparent);
        }

        .quiz-option.selected .quiz-option-shine::before {
            animation: option-shine 1.6s ease-in-out infinite;
        }

        @keyframes option-shine {
            0% { left: -60%; }
            100% { left: 130%; }
        }

        .quiz-option-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: calc(var(--card-w) * 0.1);
            height: calc(var(--card-w) * 0.1);
            min-width: 30px;
            min-height: 30px;
            border-radius: 50%;
            background: #cccccc;
            color: #ffffff;
            font-size: calc(var(--card-w) * 0.05);
            font-weight: bold;
            margin-bottom: calc(var(--card-w) * 0.02);
        }

        .quiz-option.selected {
            border-color: #009a4c;
            animation: option-glow 1.4s ease-in-out infinite;
        }

        @keyframes option-glow {
            0%, 100% {
                box-shadow: 0 0 6px rgba(0, 154, 76, 0.35), 0 0 14px rgba(0, 154, 76, 0.15);
            }
            50% {
                box-shadow: 0 0 16px rgba(0, 154, 76, 0.9), 0 0 32px rgba(0, 154, 76, 0.4);
            }
        }

        .quiz-option.selected .quiz-option-number {
            background: #009a4c;
        }

        .quiz-option:active {
            transform: scale(0.98);
        }

        .cta-button {
            background: #009a4c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            width: 72%;
            max-width: 320px;
            padding: calc(var(--card-w) * 0.045) 0;
            font-size: calc(var(--card-w) * 0.045);
            font-weight: bold;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 4vh;
            display: block;
        }

        .cta-button:active {
            transform: scale(0.97);
        }

        .cta-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-text {
            color: #c81e1e;
            font-weight: bold;
            text-align: center;
            margin: 1.5vh 0 0;
            font-size: calc(var(--card-w) * 0.036);
        }

        .success-icon {
            width: calc(var(--card-w) * 0.32);
            height: calc(var(--card-w) * 0.32);
            max-width: 140px;
            max-height: 140px;
            margin-bottom: 2vh;
            animation: success-pop 0.4s ease-out;
        }

        .success-icon-svg {
            width: 100%;
            height: 100%;
        }

        .success-icon-circle {
            fill: none;
            stroke: #009a4c;
            stroke-width: 6;
            stroke-linecap: round;
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            animation: success-circle 0.6s ease-out forwards;
        }

        .success-icon-check {
            fill: none;
            stroke: #009a4c;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 72;
            stroke-dashoffset: 72;
            animation: success-check 0.35s ease-out 0.55s forwards;
        }

        @keyframes success-pop {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes success-circle {
            to { stroke-dashoffset: 0; }
        }

        @keyframes success-check {
            to { stroke-dashoffset: 0; }
        }

        .success-title {
            color: #b3181c;
            font-size: calc(var(--card-w) * 0.08);
            text-align: center;
            margin: 0;
        }

        .success-text {
            color: #333333;
            text-align: center;
            font-size: calc(var(--card-w) * 0.042);
            margin-top: 2vh;
            max-width: 320px;
        }
    </style>
</head>
<body>
    <div class="raffle-shell">

        <section id="screen-quiz" class="screen active">
            <img class="quiz-logo" src="{{ asset('raffle_draw/01/Logo_01.png') }}" alt="Flora Margarine">
            <span class="stall-badge">Stall {{ $stall }}</span>
            <h2 class="quiz-heading">What's your favourite Flora Flavoured Dip?</h2>
            <p class="quiz-subtext">Choose one</p>

            <div class="quiz-options" id="quiz-options">
                @foreach ($dips as $dip)
                    <button
                        type="button"
                        class="quiz-option"
                        data-value="{{ $dip }}"
                        style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.55)), url('{{ asset('Button_' . $loop->iteration . '.jpg.png') }}');"
                    >
                        <span class="quiz-option-shine"></span>
                        <span class="quiz-option-number">{{ $loop->iteration }}</span>
                        <span class="quiz-option-text">{{ $dip }}</span>
                    </button>
                @endforeach
            </div>

            <p class="error-text" id="submit-error" hidden></p>
        </section>

        <section id="screen-thanks" class="screen">
            <div class="success-icon">
                <svg class="success-icon-svg" viewBox="0 0 100 100">
                    <circle class="success-icon-circle" cx="50" cy="50" r="45" />
                    <path class="success-icon-check" d="M27 52 L44 68 L75 33" />
                </svg>
            </div>
            <h1 class="success-title">Thank You!</h1>
            <p class="success-text">Your answer has been submitted for Stall {{ $stall }}. Good luck!</p>
        </section>

    </div>

    <script>
        (function () {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var errorEl = document.getElementById('submit-error');
            var submitting = false;

            document.querySelectorAll('.quiz-option').forEach(function (option) {
                option.addEventListener('click', function () {
                    if (submitting) {
                        return;
                    }

                    submitting = true;
                    errorEl.hidden = true;

                    document.querySelectorAll('.quiz-option').forEach(function (o) {
                        o.classList.remove('selected');
                    });
                    option.classList.add('selected');

                    var favouriteDip = option.dataset.value;

                    fetch(window.location.pathname, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ favourite_dip: favouriteDip })
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Submit failed');
                            }
                            return response.json();
                        })
                        .then(function () {
                            document.getElementById('screen-quiz').classList.remove('active');
                            document.getElementById('screen-thanks').classList.add('active');

                            setTimeout(function () {
                                submitting = false;
                                document.querySelectorAll('.quiz-option').forEach(function (o) {
                                    o.classList.remove('selected');
                                });

                                document.getElementById('screen-thanks').classList.remove('active');
                                document.getElementById('screen-quiz').classList.add('active');
                            }, 5000);
                        })
                        .catch(function () {
                            submitting = false;
                            option.classList.remove('selected');
                            errorEl.textContent = 'Something went wrong. Please try again.';
                            errorEl.hidden = false;
                        });
                });
            });
        })();
    </script>
</body>
</html>
