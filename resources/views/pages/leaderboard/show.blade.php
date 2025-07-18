{{-- resources/views/pages/leaderboard/show.blade.php --}}
    <!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        html, body { margin: 0; padding: 0; height: 100%; }
        body { font-family: 'Space Mono', monospace; position: relative; z-index: 0; }
        #starfield { position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: -1; background: black; pointer-events: none; }

        .neon-text {
            /* dynamischer Glanz-Effekt */
            color: {{ $design->header_color }};
            text-shadow:
                0 0 5px {{ $design->header_color }},
                0 0 10px {{ $design->header_color }},
                0 0 15px {{ $design->header_color }};
            transition: text-shadow 0.3s ease;
        }
        .neon-text:hover {
            text-shadow:
                0 0 10px {{ $design->header_color }},
                0 0 20px {{ $design->header_color }},
                0 0 30px {{ $design->header_color }};
        }
        .btn-hover {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        .btn-hover:hover {
            transform: translateY(-3px);
            opacity: 0.9;
            box-shadow: 0 5px 15px {{ $design->button_color }}80;
        }
        .btn-hover:after {
            content: "";
            position: absolute;
            top: -50%;
            left: -60%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            transition: all 0.3s ease;
        }
        .btn-hover:hover:after {
            left: 100%;
        }
        .table-row-hover {
            transition: all 0.2s ease;
            position: relative;
        }
        .table-row-hover:hover {
            transform: scale(1.01);
            background-color: rgba(0,255,170,0.1) !important;
            box-shadow: 0 0 15px {{ $design->button_color }}40;
        }
        .table-row-hover:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: {{ $design->button_color }};
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .table-row-hover:hover:before {
            opacity: 1;
        }
        .podium-hover {
            transition: all 0.3s ease;
            position: relative;
        }
        .podium-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px {{ $design->button_color }}80;
            z-index: 20;
        }
        .podium-hover:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: {{ $design->button_color }};
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }
        .podium-hover:hover:after {
            transform: scaleX(1);
            transform-origin: left;
        }
        .countdown-box {
            transition: all 0.3s ease;
        }
        .countdown-box:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px {{ $design->button_color }}80;
        }
        .prize-box {
            transition: all 0.3s ease;
        }
        .prize-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px {{ $design->button_color }}80;
        }
        .logo-hover {
            transition: all 0.5s ease;
        }
        .logo-hover:hover {
            transform: rotate(5deg) scale(1.1);
            filter: drop-shadow(0 0 10px {{ $design->button_color }});
        }
        .modal-content {
            transition: all 0.3s ease;
        }
        .modal-content:hover {
            box-shadow: 0 0 30px {{ $design->button_color }}80;
        }
        .rank-cell {
            transition: all 0.3s ease;
        }
        .table-row-hover:hover .rank-cell {
            transform: scale(1.2);
            color: {{ $design->button_color }};
        }
        .username-cell {
            transition: all 0.3s ease;
        }
        .table-row-hover:hover .username-cell {
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="relative overflow-x-hidden font-[Space_Mono]"
      style="background-color: {{ $design->background_color }}; color: {{ $design->header_color }};"
>
<canvas id="starfield"></canvas>

<div class="max-w-6xl mx-auto px-4 py-10 text-center relative z-10">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('storage/' . $design->logo) }}" alt="Logo" class="h-24 logo-hover">
    </div>

    <!-- Header -->
    <h1 class="text-5xl font-bold mb-4 neon-text">LEADERBOARD</h1>

    <!-- Description -->
    <p class="text-lg mb-8 max-w-2xl mx-auto">
        Play under code
        <span class="font-bold hover:text-{{ $design->button_color }} transition-colors duration-300" style="color: {{ $design->button_color }};">weenergamba</span>
        on Roobet to get rewards and participate in giveaways.<br>
        <span class="text-red-400 hover:text-red-300 transition-colors duration-300">*Winners must be member of the Discord Server to claim prize.</span>
    </p>

    <!-- Buttons -->
    <div class="flex justify-center gap-4 mb-12">
        <a href="{{ $design->play_now_url }}" target="_blank"
           class="btn-hover text-black font-bold py-3 px-6 rounded-lg text-lg"
           style="background-color: {{ $design->button_color }}; border: 2px solid {{ $design->button_color }};">
            PLAY NOW
        </a>
        <button onclick="openRulesModal()"
                class="btn-hover font-bold py-3 px-6 rounded-lg text-lg"
                style="background: transparent; color: {{ $design->button_color }}; border: 2px solid {{ $design->button_color }};">
            RULES
        </button>
    </div>

    @if (! $leaderboard)
        <p class="text-red-400 text-xl mt-10 hover:text-red-300 transition-colors duration-300">❌ No active leaderboard currently.</p>
    @else
        <!-- Countdown -->
        @if ($leaderboard->status === 'running' && $leaderboard->end_date)
            <div class="bg-gray-900/50 backdrop-blur-sm p-6 rounded-lg mb-8 max-w-2xl mx-auto countdown-box"
                 style="border: 1px solid {{ $design->button_color }};">
                <h2 class="text-2xl font-bold mb-2 hover:text-{{ $design->button_color }} transition-colors duration-300" style="color: {{ $design->button_color }};">
                    ⏳ LEADERBOARD COUNTDOWN
                </h2>
                <p class="text-3xl font-mono hover:text-white" id="countdown">Loading...</p>
            </div>
        @elseif ($leaderboard->status === 'paused')
            <p class="text-yellow-400 font-semibold text-xl mb-8 hover:text-yellow-300 transition-colors duration-300">⏸️ The competition is currently paused.</p>
        @else
            <p class="text-red-400 font-semibold text-xl mb-8 hover:text-red-300 transition-colors duration-300">⛔ The competition has ended.</p>
        @endif

        <!-- Prize Info -->
        @if ($leaderboard->prize_info)
            <div class="p-6 rounded-lg mb-12 max-w-4xl mx-auto shadow-lg prize-box"
                 style="background: rgba(0, 255, 170, 0.1); border: 1px solid {{ $design->button_color }};">
                <h2 class="text-3xl font-bold mb-4 hover:text-{{ $design->button_color }} transition-colors duration-300" style="color: {{ $design->button_color }};">🏆 PRIZE POOL</h2>
                <p class="text-xl text-white/90 hover:text-white transition-colors duration-300">{!! nl2br(e($leaderboard->prize_info)) !!}</p>
            </div>
        @endif

        <!-- Top 3 Winners -->
        @if ($entries->count() >= 3)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 max-w-6xl mx-auto">
                {{-- 2nd Place --}}
                <div class="p-6 rounded-lg shadow-lg podium-hover"
                     style="background: linear-gradient(to bottom, rgba(31, 41, 55, .8), rgba(17, 24, 39, .8)); border-top: 4px solid {{ $design->button_color }};">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $design->second_place_gif) }}" alt="2nd Place" class="h-32 hover:scale-110 transition-transform duration-300">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-300 mb-2 hover:text-white transition-colors duration-300">2ND PLACE</h3>
                    <p class="mt-2 text-lg font-medium hover:text-white transition-colors duration-300">
                        {{ Str::mask($entries[1]->username, '*', 2, null) }}
                    </p>
                    <p class="font-bold text-xl hover:text-{{ $design->button_color }} transition-colors duration-300">
                        {{ number_format($entries[1]->weighted_wagered, 2) }}{!! $design->currency_symbol !!}
                    </p>
                    @if(! empty($leaderboard->second_place_reward))
                        <p class="text-gray-300 font-semibold mt-1 hover:text-white transition-colors duration-300">
                            🎖 Prize:
                            {!! $design->currency_symbol !!}
                            {{ Str::replace('€', '', $leaderboard->second_place_reward) }}
                        </p>
                    @endif
                </div>

                {{-- 1st Place --}}
                <div class="p-6 rounded-lg shadow-lg transform scale-105 -mt-4 z-10 podium-hover"
                     style="background: linear-gradient(to bottom, {{ $design->button_color }}33, {{ $design->button_color }}66); border-top: 4px solid {{ $design->button_color }};">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $design->first_place_gif) }}" alt="1st Place" class="h-40 hover:rotate-2 transition-transform duration-500">
                    </div>
                    <h3 class="text-2xl font-bold mb-2 hover:text-white transition-colors duration-300">1ST PLACE</h3>
                    <p class="mt-2 text-lg font-medium hover:text-white transition-colors duration-300">
                        {{ Str::mask($entries[0]->username, '*', 2, null) }}
                    </p>
                    <p class="font-bold text-2xl hover:scale-105 transition-transform duration-300">
                        {{ number_format($entries[0]->weighted_wagered, 2) }}{!! $design->currency_symbol !!}
                    </p>
                    @if(! empty($leaderboard->first_place_reward))
                        <p class="font-semibold mt-1 hover:scale-105 transition-transform duration-300" style="color: {{ $design->button_color }};">
                            🏆 Prize:
                            {!! $design->currency_symbol !!}
                            {{ Str::replace('€', '', $leaderboard->first_place_reward) }}
                        </p>
                    @endif
                </div>

                {{-- 3rd Place --}}
                <div class="p-6 rounded-lg shadow-lg podium-hover"
                     style="background: linear-gradient(to bottom, rgba(56, 30, 121, .2), rgba(56, 30, 121, .4)); border-top: 4px solid {{ $design->button_color }};">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $design->third_place_gif) }}" alt="3rd Place" class="h-28 hover:-rotate-6 transition-transform duration-300">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-300 mb-2 hover:text-white transition-colors duration-300">3RD PLACE</h3>
                    <p class="mt-2 text-lg font-medium hover:text-white transition-colors duration-300">
                        {{ Str::mask($entries[2]->username, '*', 2, null) }}
                    </p>
                    <p class="font-bold text-xl hover:text-amber-400 transition-colors duration-300">
                        {{ number_format($entries[2]->weighted_wagered, 2) }}{!! $design->currency_symbol !!}
                    </p>
                    @if(! empty($leaderboard->third_place_reward))
                        <p class="text-amber-400 font-semibold mt-1 hover:text-amber-300 transition-colors duration-300">
                            🥉 Prize:
                            {!! $design->currency_symbol !!}
                            {{ Str::replace('€', '', $leaderboard->third_place_reward) }}
                        </p>
                    @endif
                </div>
            </div>
        @endif


        <!-- Full Leaderboard Table -->
        <div class="rounded-xl shadow-xl overflow-hidden mb-12 max-w-6xl mx-auto hover:shadow-2xl transition-shadow duration-300"
             style="background: rgba(17, 24, 39, .5); border: 1px solid {{ $design->button_color }};">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead style="background: {{ $design->button_color }}22;">
                    <tr class="hover:bg-{{ $design->button_color }}33 transition-colors duration-300">
                        <th></th>
                        <th class="p-4 text-center font-bold text-lg hover:scale-110 transition-transform duration-300" style="color: {{ $design->button_color }};">RANK</th>
                        <th class="p-4 font-bold text-lg hover:tracking-wider transition-all duration-300" style="color: {{ $design->button_color }};">USERNAME</th>
                        <th class="p-4 font-bold text-lg hover:text-white transition-colors duration-300" style="color: {{ $design->button_color }};">PRIZE</th>
                        <th class="p-4 font-bold text-lg hover:text-white transition-colors duration-300" style="color: {{ $design->button_color }};">WAGERED</th>
                        <th class="p-4 font-bold text-lg hover:text-white transition-colors duration-300" style="color: {{ $design->button_color }};">WEIGHTED WAGER</th>
                        <th class="p-4 font-bold text-lg hover:text-white transition-colors duration-300" style="color: {{ $design->button_color }};">FAVORITE GAME</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries as $entry)
                        <tr class="table-row-hover"
                            style="background: {{ $loop->index < 3 ? $design->button_color.'22' : 'rgba(17,24,39,.3)' }};">
                            <td class="p-4 text-center text-lg font-bold rank-cell">{{ $loop->iteration }}</td>
                            <td class="p-4 font-medium username-cell">{{ Str::mask($entry->username, '*', 2, null) }}</td>

                            {{-- Prize --}}
                            <td class="p-4 hover:text-{{ $design->button_color }} transition-colors duration-300">
                                @php
                                    // 1-basiert (Rank)
                                    $pos = $loop->iteration;

                                    // aus prize_tiers das passende Tier holen
                                    $tier = collect($leaderboard->prize_tiers ?? [])
                                            ->first(fn($t) => (int) data_get($t, 'position') === $pos);
                                @endphp

                                @if($tier)
                                    {!! $design->currency_symbol !!}
                                    {{ number_format((float) $tier['reward'], 2) }}
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td class="p-4 font-mono hover:text-white transition-colors duration-300">
                                {!! $design->currency_symbol !!}{{ number_format($entry->wagered, 2) }}
                            </td>
                            <td class="p-4 font-mono font-bold hover:text-{{ $design->button_color }} transition-colors duration-300">
                                {!! $design->currency_symbol !!}{{ number_format($entry->weighted_wagered, 2) }}
                            </td>
                            <td class="p-4 text-gray-300 hover:text-white transition-colors duration-300">
                                {{ Str::limit($entry->favorite_game_title, 30) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        @if ($leaderboard->status === 'running' && $leaderboard->end_date)
            <script>
                const countdown = document.getElementById('countdown');
                const end = new Date("{{ $leaderboard->end_date->format('Y-m-d H:i:s') }}").getTime();
                function updateCountdown() {
                    const now = Date.now(), diff = end - now;
                    if (diff <= 0) {
                        countdown.textContent = 'COMPETITION ENDED!';
                        return;
                    }
                    const d = Math.floor(diff / (1000*60*60*24));
                    const h = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
                    const m = Math.floor((diff % (1000*60*60)) / (1000*60));
                    const s = Math.floor((diff % (1000*60)) / 1000);
                    countdown.textContent = `${d}d ${h}h ${m}m ${s}s`;
                }
                setInterval(updateCountdown, 1000);
                updateCountdown();
            </script>
        @endif
    @endif

</div>

<!-- Rules Modal -->
<div id="rulesModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-gray-900/90 rounded-xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto modal-content"
         style="border: 1px solid {{ $design->button_color }};">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold neon-text hover:text-{{ $design->button_color }} transition-colors duration-300">COMPETITION RULES</h2>
            <button onclick="closeRulesModal()" class="text-gray-400 hover:text-{{ $design->button_color }} text-2xl transition-colors duration-300">&times;</button>
        </div>
        <div class="text-left space-y-4 text-white/90 hover:text-white transition-colors duration-300">
            {!! $design->rules_content !!}
        </div>
        <div class="mt-8 text-center">
            <button onclick="closeRulesModal()" class="btn-hover font-bold py-2 px-6 rounded-lg text-lg"
                    style="background: transparent; color: {{ $design->button_color }}; border: 2px solid {{ $design->button_color }};">
                CLOSE
            </button>
        </div>
    </div>
</div>

<script>
    function openRulesModal() {
        document.getElementById('rulesModal').classList.remove('hidden');
    }

    function closeRulesModal() {
        document.getElementById('rulesModal').classList.add('hidden');
    }

    // Starfield Animation
    document.addEventListener("DOMContentLoaded", () => {
        const canvas = document.getElementById("starfield");
        const ctx = canvas.getContext("2d");
        const numStars = 800;
        const speed = 5;
        let stars = [];

        function setCanvasSize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        class Star {
            constructor(x, y, z, size) {
                this.x = x;
                this.y = y;
                this.z = z;
                this.size = size;
            }

            update() {
                this.z -= speed;
                if (this.z <= 0) {
                    this.reset();
                }
            }

            reset() {
                this.z = Math.random() * canvas.width * 0.8 + 1;
                this.x = Math.random() * (canvas.width * 2) - canvas.width;
                this.y = Math.random() * (canvas.height * 2) - canvas.height;
                this.size = Math.random() * 2 + 1;
            }

            draw() {
                const sx = ((this.x / this.z) * canvas.width) / 2 + canvas.width / 2;
                const sy = ((this.y / this.z) * canvas.height) / 2 + canvas.height / 2;
                const rawRadius = (1 - this.z / canvas.width) * this.size;
                const radius = Math.max(rawRadius, 0.1); // Mindestgröße z. B. 0.1

                ctx.beginPath();
                ctx.arc(sx, sy, radius, 0, Math.PI * 2);
                ctx.fillStyle = "white";
                ctx.fill();
            }

        }

        function initStars() {
            stars = Array.from(
                { length: numStars },
                () =>
                    new Star(
                        Math.random() * (canvas.width * 2) - canvas.width,
                        Math.random() * (canvas.height * 2) - canvas.height,
                        Math.random() * canvas.width,
                        Math.random() * 2 + 1
                    )
            );
        }

        function updateStars() {
            stars.forEach((star) => star.update());
        }

        function drawStars() {
            ctx.fillStyle = "black";
            ctx.fillRect(0, 0, canvas.width, canvas.height); // Hintergrund schwarz
            stars.forEach((star) => star.draw());
        }

        function animate() {
            updateStars();
            drawStars();
            requestAnimationFrame(animate);
        }

        window.addEventListener("resize", () => {
            setCanvasSize();
            initStars();
        });

        setCanvasSize();
        initStars();
        animate();
    });

</script>

</body>
</html>
