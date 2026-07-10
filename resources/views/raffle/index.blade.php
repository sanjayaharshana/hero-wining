<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Flora Margarine</title>
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
            max-width: 480px;
            margin: 0 auto;
            position: relative;
            overflow-x: hidden;
        }

        @media (min-width: 481px) {
            html, body {
                height: 100%;
            }

            body {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .raffle-shell {
                width: 100%;
            }

            .screen {
                min-height: min(100dvh, 900px);
                max-height: 100dvh;
            }
        }

        .screen {
            display: none;
            flex-direction: column;
            align-items: center;
            min-height: 100dvh;
            width: 100%;
            padding: 6vh 7% 5vh;
            overflow-y: auto;
            box-sizing: border-box;
        }

        #screen-2,
        #screen-3,
        #screen-4 {
            justify-content: center;
        }

        .screen.active {
            display: flex;
        }

        @media (max-height: 480px) {
            .screen {
                padding: 3vh 7% 3vh;
            }

            .headline-logo {
                margin-top: 3vh;
            }

            .cta-button {
                margin-bottom: 2vh;
            }
        }

        .spacer {
            flex: 1 1 auto;
        }

        .headline-logo {
            width: 92%;
            max-width: 420px;
            margin-top: 10vh;
        }

        .tagline {
            width: 82%;
            max-width: 380px;
            margin-top: 4vh;
        }

        .tagline.small {
            width: 70%;
            margin-top: 2vh;
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
            margin-bottom: 6vh;
            display: block;
        }

        .cta-button:active {
            transform: scale(0.97);
        }

        .cta-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .question-text {
            width: 92%;
            margin-top: 0;
            font-size: calc(var(--card-w) * 0.06);
            font-weight: bold;
            color: #1a1a1a;
            text-align: center;
        }

        .details-form {
            width: 100%;
            margin-top: 2vh;
            margin-bottom: 3vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5vh;
        }

        .field-wrap {
            position: relative;
            width: 92%;
        }

        .field-wrap input,
        .field-wrap select {
            width: 100%;
            border: 2px solid #009a4c;
            border-radius: 8px;
            outline: none;
            background: #ffffff;
            font-size: calc(var(--card-w) * 0.042);
            font-weight: bold;
            color: #1a1a1a;
            padding: calc(var(--card-w) * 0.035);
        }

        .field-wrap select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23009a4c' stroke-width='3'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 18px;
            padding-right: calc(var(--card-w) * 0.08);
        }

        .field-wrap select:invalid {
            color: #9a9a9a;
        }

        .field-wrap input::placeholder {
            color: #9a9a9a;
            font-weight: bold;
        }

        .field-wrap input:focus,
        .field-wrap select:focus {
            border-color: #007a3d;
            box-shadow: 0 0 0 3px rgba(0, 154, 76, 0.2);
        }

        .error-text {
            color: #c81e1e;
            font-weight: bold;
            text-align: center;
            margin: 1.5vh 0 0;
            font-size: calc(var(--card-w) * 0.036);
        }

        .verify-row {
            width: 92%;
            margin-top: 2vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1vh;
        }

        .verify-label {
            font-size: calc(var(--card-w) * 0.032);
            font-weight: bold;
            letter-spacing: 0.05em;
            color: #b3181c;
            text-transform: uppercase;
        }

        .verify-value {
            font-size: calc(var(--card-w) * 0.046);
            font-weight: bold;
            color: #241111;
            word-break: break-word;
        }

        .button-stack {
            width: 100%;
            margin-top: 4vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button-stack .cta-button {
            margin-bottom: 3vh;
        }

        .button-stack .cta-button:last-child {
            margin-bottom: 0;
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

        #screen-quiz {
            justify-content: center;
        }

        .quiz-heading {
            width: 92%;
            font-size: calc(var(--card-w) * 0.055);
            font-weight: bold;
            color: #1a1a1a;
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
            flex-direction: column;
            align-items: center;
            gap: 1.5vh;
        }

        .quiz-option {
            width: 92%;
            max-width: 380px;
            padding: calc(var(--card-w) * 0.03) calc(var(--card-w) * 0.04);
            border: 2px solid #cccccc;
            border-radius: 8px;
            background: #ffffff;
            color: #1a1a1a;
            font-size: calc(var(--card-w) * 0.04);
            font-weight: bold;
            text-align: left;
            cursor: pointer;
        }

        .quiz-option.selected {
            border-color: #009a4c;
            background: rgba(0, 154, 76, 0.08);
            color: #007a3d;
        }

        .quiz-option:active {
            transform: scale(0.98);
        }

        #quiz-continue {
            margin-top: 3vh;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="raffle-shell">

        {{-- Screen 1: Landing --}}
        <section id="screen-1" class="screen active">
            <img class="headline-logo" src="{{ asset('raffle_draw/01/Logo_01.png') }}" alt="Accelerating towards the future">
            <div class="spacer"></div>
            <button type="button" class="cta-button" id="btn-start">Start</button>
        </section>

        {{-- Screen 2: Enter details --}}
        <section id="screen-2" class="screen">
            <h2 class="question-text">What is your name?</h2>

            <form class="details-form" id="details-form" novalidate>
                <div class="field-wrap">
                    <input type="text" id="name" name="name" placeholder="Name" autocomplete="name" required>
                </div>
                <div class="field-wrap">
                    <select id="stall_number" name="stall_number" required>
                        <option value="" disabled selected>Stall Number</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                    </select>
                </div>
            </form>
            <p class="error-text" id="details-error" hidden></p>

            <button type="button" class="cta-button" id="btn-continue">Continue</button>
        </section>

        {{-- Screen Quiz: Flora flavour quiz --}}
        <section id="screen-quiz" class="screen">
            <h2 class="quiz-heading">What's your favourite Flora Flavoured Dip?</h2>
            <p class="quiz-subtext">Choose one</p>

            <div class="quiz-options" id="quiz-options">
                <button type="button" class="quiz-option" data-value="Roasted Garlic and Herbs">Roasted Garlic and Herbs</button>
                <button type="button" class="quiz-option" data-value="Sundried Tomato and Hazelnuts">Sundried Tomato and Hazelnuts</button>
                <button type="button" class="quiz-option" data-value="Smoked Paprika and Lime">Smoked Paprika and Lime</button>
                <button type="button" class="quiz-option" data-value="Olives and Capers">Olives and Capers</button>
                <button type="button" class="quiz-option" data-value="Bee Honey and Flaky Salt">Bee Honey and Flaky Salt</button>
            </div>

            <button type="button" class="cta-button" id="quiz-continue" disabled>Continue</button>
        </section>

        {{-- Screen 3: Verify details --}}
        <section id="screen-3" class="screen">
            <h2 class="question-text">Verify Your Details</h2>

            <div class="verify-row">
                <span class="verify-label">Name</span>
                <span class="verify-value" id="verify-name"></span>
            </div>
            <div class="verify-row">
                <span class="verify-label">Stall Number</span>
                <span class="verify-value" id="verify-stall-number"></span>
            </div>
            <div class="verify-row">
                <span class="verify-label">Favourite Dip</span>
                <span class="verify-value" id="verify-dip"></span>
            </div>
            <p class="error-text" id="submit-error" hidden></p>

            <div class="button-stack">
                <button type="button" class="cta-button" id="btn-edit">Edit Details</button>
                <button type="button" class="cta-button" id="btn-submit">Confirm &amp; Submit</button>
            </div>
        </section>

        {{-- Screen 4: Thank you --}}
        <section id="screen-4" class="screen">
            <h1 class="success-title">Thank You!</h1>
            <p class="success-text">Your entry has been submitted. Good luck!</p>
            <div class="button-stack">
                <button type="button" class="cta-button" id="btn-home">Home</button>
            </div>
        </section>

    </div>

    <script>
        (function () {
            var state = {};
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            function showScreen(id) {
                document.querySelectorAll('.screen').forEach(function (s) {
                    s.classList.remove('active');
                });
                document.getElementById(id).classList.add('active');
                window.scrollTo(0, 0);
            }

            document.getElementById('btn-start').addEventListener('click', function () {
                showScreen('screen-2');
            });

            var quizContinueBtn = document.getElementById('quiz-continue');

            document.querySelectorAll('.quiz-option').forEach(function (option) {
                option.addEventListener('click', function () {
                    document.querySelectorAll('.quiz-option').forEach(function (o) {
                        o.classList.remove('selected');
                    });
                    option.classList.add('selected');
                    state.favourite_dip = option.dataset.value;
                    quizContinueBtn.disabled = false;
                });
            });

            quizContinueBtn.addEventListener('click', function () {
                document.getElementById('verify-dip').textContent = state.favourite_dip;
                showScreen('screen-3');
            });

            document.getElementById('btn-continue').addEventListener('click', function () {
                var name = document.getElementById('name').value.trim();
                var stallNumber = document.getElementById('stall_number').value;
                var errorEl = document.getElementById('details-error');

                if (!name) {
                    errorEl.textContent = 'Please enter your name.';
                    errorEl.hidden = false;
                    return;
                }

                if (!stallNumber) {
                    errorEl.textContent = 'Please select your stall number.';
                    errorEl.hidden = false;
                    return;
                }

                errorEl.hidden = true;
                state.name = name;
                state.stall_number = stallNumber;

                document.getElementById('verify-name').textContent = name;
                document.getElementById('verify-stall-number').textContent = stallNumber;

                showScreen('screen-quiz');
            });

            document.getElementById('btn-edit').addEventListener('click', function () {
                showScreen('screen-2');
            });

            document.getElementById('btn-submit').addEventListener('click', function () {
                var submitBtn = document.getElementById('btn-submit');
                var errorEl = document.getElementById('submit-error');
                errorEl.hidden = true;
                submitBtn.disabled = true;

                fetch('{{ route('raffle-entries.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(state)
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Submit failed');
                        }
                        return response.json();
                    })
                    .then(function () {
                        showScreen('screen-4');
                    })
                    .catch(function () {
                        errorEl.textContent = 'Something went wrong. Please try again.';
                        errorEl.hidden = false;
                    })
                    .finally(function () {
                        submitBtn.disabled = false;
                    });
            });

            function resetEntryForm() {
                state = {};

                document.getElementById('name').value = '';
                document.getElementById('stall_number').value = '';
                document.getElementById('details-error').hidden = true;

                document.querySelectorAll('.quiz-option').forEach(function (option) {
                    option.classList.remove('selected');
                });
                quizContinueBtn.disabled = true;

                document.getElementById('verify-name').textContent = '';
                document.getElementById('verify-stall-number').textContent = '';
                document.getElementById('verify-dip').textContent = '';
                document.getElementById('submit-error').hidden = true;
            }

            document.getElementById('btn-home').addEventListener('click', function () {
                resetEntryForm();
                showScreen('screen-1');
            });
        })();
    </script>
</body>
</html>
