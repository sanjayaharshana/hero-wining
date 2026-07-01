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
            background: #0a0000;
            font-family: Arial, Helvetica, sans-serif;
            color: #fff;
        }

        .wrap {
            max-width: 720px;
            margin: 0 auto;
            padding: 20px 16px 60px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 20px;
            margin: 0;
        }

        nav a {
            color: #d99;
            font-size: 13px;
            text-decoration: none;
            margin-right: 14px;
        }

        nav a:hover {
            color: #fff;
        }

        form.logout button {
            padding: 8px 14px;
            border: 1px solid #5a1414;
            background: #260808;
            color: #ff9d9d;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .status-line {
            text-align: center;
            color: #d99;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .board {
            position: relative;
            background: #000;
            border: 6px solid #161616;
            border-radius: 14px;
            padding: 40px 20px;
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

        .led-text {
            position: relative;
            font-family: 'VT323', 'Courier New', monospace;
            color: #ff2b2b;
            text-shadow: 0 0 6px #ff0000, 0 0 18px #ff0000cc;
            font-size: clamp(30px, 8vw, 58px);
            text-align: center;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            min-height: 1.3em;
            line-height: 1.3em;
            word-break: break-word;
        }

        .led-text.spinning {
            animation: flicker 0.12s infinite;
        }

        .led-sub {
            position: relative;
            font-family: 'VT323', 'Courier New', monospace;
            color: #ffb020;
            text-shadow: 0 0 6px #ff8800aa;
            text-align: center;
            font-size: clamp(16px, 4.5vw, 26px);
            letter-spacing: 0.1em;
            margin-top: 6px;
            min-height: 1.2em;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; }
            45% { opacity: 0.85; }
            50% { opacity: 0.55; }
            55% { opacity: 0.9; }
        }

        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 26px;
        }

        .draw-btn {
            width: 100%;
            max-width: 320px;
            padding: 18px;
            border: none;
            border-radius: 10px;
            background: #e11d1d;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 0 25px rgba(225, 29, 29, 0.55);
        }

        .draw-btn:hover:not(:disabled) {
            background: #c81616;
        }

        .draw-btn:disabled {
            background: #4a1414;
            color: #a06565;
            cursor: not-allowed;
            box-shadow: none;
        }

        .reset-link {
            background: none;
            border: none;
            color: #a06565;
            font-size: 13px;
            text-decoration: underline;
            cursor: pointer;
        }

        .winners {
            margin-top: 36px;
        }

        .winners h2 {
            font-size: 15px;
            color: #e11d1d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
        }

        .winners ol {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .winners li {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            background: #150505;
            border: 1px solid #3a0d0d;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .winners li .name {
            font-weight: bold;
        }

        .winners li .time {
            color: #d99;
            font-size: 12px;
            white-space: nowrap;
        }

        .empty-winners {
            color: #a06565;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div>
                <h1>Grand Draw &mdash; Winner Selection</h1>
                <nav><a href="{{ route('admin.entries') }}">&larr; All Entries</a></nav>
            </div>
            <form class="logout" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Log Out</button>
            </form>
        </header>

        <div class="status-line" id="status-line">
            <span id="remaining-count">{{ $eligibleCount }}</span> eligible {{ Str::plural('entry', $eligibleCount) }} remaining
        </div>

        <div class="board">
            <div class="led-text" id="led-text">Press Draw to Start</div>
            <div class="led-sub" id="led-sub">&nbsp;</div>
        </div>

        <div class="actions">
            <button type="button" class="draw-btn" id="draw-btn" @if($eligibleCount === 0) disabled @endif>
                Draw Winner
            </button>
            <button type="button" class="reset-link" id="reset-btn">Reset all winners</button>
        </div>

        <div class="winners">
            <h2>Winners Drawn So Far</h2>
            <ol id="winners-list">
                @forelse ($winners as $winner)
                    <li>
                        <span class="name">{{ $winner->first_name }} {{ $winner->last_name }}</span>
                        <span class="time">{{ $winner->won_at?->format('Y-m-d H:i') }}</span>
                    </li>
                @empty
                    <li class="empty-winners" style="border:none;background:none;">No winners drawn yet.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <form id="reset-form" method="POST" action="{{ route('admin.winner-draw.reset') }}" style="display:none;">
        @csrf
    </form>

    <script>
        (function () {
            var pool = @json($pool->map(fn ($entry) => $entry->first_name . ' ' . $entry->last_name)->values());
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var drawBtn = document.getElementById('draw-btn');
            var ledText = document.getElementById('led-text');
            var ledSub = document.getElementById('led-sub');
            var remainingEl = document.getElementById('remaining-count');
            var statusLine = document.getElementById('status-line');
            var winnersList = document.getElementById('winners-list');
            var spinTimer = null;

            function randomFrom(list) {
                return list[Math.floor(Math.random() * list.length)];
            }

            function setRemainingText(count) {
                remainingEl.textContent = count;
                var label = count === 1 ? 'entry' : 'entries';
                statusLine.childNodes[statusLine.childNodes.length - 1].textContent = ' eligible ' + label + ' remaining';
            }

            drawBtn.addEventListener('click', function () {
                if (pool.length === 0) {
                    return;
                }

                drawBtn.disabled = true;
                ledSub.textContent = ' ';
                ledText.classList.add('spinning');

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
                        drawBtn.disabled = true;
                        return;
                    }

                    var winner = result.data;
                    var fullName = winner.first_name + ' ' + winner.last_name;

                    ledText.textContent = fullName;
                    ledSub.textContent = winner.mobile_number_masked;

                    pool = pool.filter(function (name) {
                        return name !== fullName;
                    });

                    setRemainingText(winner.remaining);

                    var emptyItem = winnersList.querySelector('.empty-winners');
                    if (emptyItem) {
                        emptyItem.remove();
                    }

                    var li = document.createElement('li');
                    var nameSpan = document.createElement('span');
                    nameSpan.className = 'name';
                    nameSpan.textContent = fullName;
                    var timeSpan = document.createElement('span');
                    timeSpan.className = 'time';
                    timeSpan.textContent = 'just now';
                    li.appendChild(nameSpan);
                    li.appendChild(timeSpan);
                    winnersList.insertBefore(li, winnersList.firstChild);

                    drawBtn.disabled = winner.remaining === 0;
                }).catch(function () {
                    clearInterval(spinTimer);
                    ledText.classList.remove('spinning');
                    ledText.textContent = 'Something Went Wrong';
                    ledSub.textContent = 'Please try again.';
                    drawBtn.disabled = pool.length === 0;
                });
            });

            document.getElementById('reset-btn').addEventListener('click', function () {
                if (confirm('Reset all winners? This clears every drawn winner and makes all entries eligible again.')) {
                    document.getElementById('reset-form').submit();
                }
            });
        })();
    </script>
</body>
</html>
