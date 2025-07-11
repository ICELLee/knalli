<!DOCTYPE html>
<html lang="en" class="bg-black text-white antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: 'Space Mono', monospace;
            position: relative;
            z-index: 0;
        }

        #starfield {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: black;
            pointer-events: none;
        }

        .neon-text {
            text-shadow: 0 0 5px #00ffaa, 0 0 10px #00ffaa, 0 0 15px #00ffaa;
        }
        .btn-hover {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 170, 0.5);
        }
        .btn-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 255, 170, 0.8);
        }
        .table-row-hover {
            transition: all 0.2s ease;
        }
        .table-row-hover:hover {
            background-color: rgba(0, 255, 170, 0.1) !important;
            transform: scale(1.01);
        }
        .podium-hover {
            transition: all 0.3s ease;
        }
        .podium-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 255, 170, 0.3);
        }
    </style>
</head>
<body class="relative bg-black text-white overflow-x-hidden font-[Space_Mono]">
<!-- Starfield Background -->
<canvas id="starfield"></canvas>

<div class="max-w-6xl mx-auto px-4 py-10 text-center text-white relative z-10">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="https://knallirewards.com/logo/logopond.png" alt="Logo" class="h-24 neon-logo">
    </div>

    <!-- Header -->
    <h1 class="text-5xl font-bold mb-4 text-[#00ffaa] neon-text">LEADERBOARD</h1>

    <!-- Description -->
    <p class="text-lg mb-8 max-w-2xl mx-auto">
        Play under code <span class="font-bold text-[#00ffaa]">weenergamba</span> on Roobet to get rewards and participate in giveaways.<br>
        <span class="text-red-400">*Winners must be member of the Discord Server to claim prize.</span>
    </p>

    <!-- Buttons -->
    <div class="flex justify-center gap-4 mb-12">
        <a href="https://roobet.com/?ref=weenergamba" target="_blank" class="bg-[#00ffaa] hover:bg-[#00e699] text-black font-bold py-3 px-6 rounded-lg text-lg btn-hover transition-all duration-300">
            PLAY NOW
        </a>
        <button onclick="openRulesModal()" class="bg-transparent border-2 border-[#00ffaa] text-[#00ffaa] font-bold py-3 px-6 rounded-lg text-lg btn-hover transition-all duration-300">
            RULES
        </button>
    </div>

    @if (!$leaderboard)
        <p class="text-red-400 text-xl mt-10">❌ No active leaderboard currently.</p>
    @else
        <!-- Countdown -->
        @if ($leaderboard->status === 'running' && $leaderboard->end_date)
            <div class="bg-gray-900/50 backdrop-blur-sm p-6 rounded-lg mb-8 max-w-2xl mx-auto border border-[#00ffaa]/30">
                <h2 class="text-2xl font-bold text-[#00ffaa] mb-2">⏳ LEADERBOARD COUNTDOWN</h2>
                <p class="text-3xl font-mono text-[#00ffaa]" id="countdown">Loading...</p>
            </div>
        @elseif ($leaderboard->status === 'paused')
            <p class="text-yellow-400 font-semibold text-xl mb-8">⏸️ The competition is currently paused.</p>
        @else
            <p class="text-red-400 font-semibold text-xl mb-8">⛔ The competition has ended.</p>
        @endif

        <!-- Prize Info -->
        @if ($leaderboard->prize_info)
            <div class="bg-gradient-to-r from-[#00ffaa]/10 to-[#00aaff]/10 p-6 rounded-lg mb-12 max-w-4xl mx-auto shadow-lg border border-[#00ffaa]/20">
                <h2 class="text-3xl font-bold text-[#00ffaa] mb-4">🏆 PRIZE POOL</h2>
                <p class="text-xl text-white/90">{{ $leaderboard->prize_info }}</p>
            </div>
        @endif

        <!-- Top 3 Winners -->
        @if ($entries->count() >= 3)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 max-w-6xl mx-auto">
                <!-- 2nd Place -->
                <div class="bg-gradient-to-b from-gray-800/80 to-gray-900/80 p-6 rounded-lg shadow-lg transform podium-hover border-t-4 border-gray-400">
                    <div class="flex justify-center mb-4">
                        <img src="https://knallirewards.com/svg/2.gif" alt="2nd Place" class="h-32">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-300 mb-2">2ND PLACE</h3>
                    <p class="text-[#00ffaa] font-bold text-xl">{{ number_format($entries[1]->weighted_wagered, 2) }} €</p>
                    <p class="text-gray-300 font-semibold mt-1">🎖 Prize: {{ $leaderboard->second_place_reward ?? '500 €' }}</p>
                </div>

                <!-- 1st Place -->
                <div class="bg-gradient-to-b from-[#00ffaa]/20 to-[#00ffaa]/40 p-6 rounded-lg shadow-lg transform scale-105 -mt-4 z-10 podium-hover border-t-4 border-[#00ffaa]">
                    <div class="flex justify-center mb-4">
                        <img src="https://knallirewards.com/svg/1.gif" alt="1st Place" class="h-40">
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">1ST PLACE</h3>
                    <p class="text-[#00ffaa] font-bold text-2xl">{{ number_format($entries[0]->weighted_wagered, 2) }} €</p>
                    <p class="text-yellow-400 font-semibold mt-1">🏆 Prize: {{ $leaderboard->first_place_reward ?? '1000 €' }}</p>
                </div>

                <!-- 3rd Place -->
                <div class="bg-gradient-to-b from-[#381e79]/20 to-[#381e79]/40 p-6 rounded-lg shadow-lg transform podium-hover border-t-4 border-[#381e79]">
                    <div class="flex justify-center mb-4">
                        <img src="https://knallirewards.com/svg/3.gif" alt="3rd Place" class="h-28">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-300 mb-2">3RD PLACE</h3>
                    <p class="text-[#00ffaa] font-bold text-xl">{{ number_format($entries[2]->weighted_wagered, 2) }} €</p>
                    <p class="text-amber-400 font-semibold mt-1">🥉 Prize: {{ $leaderboard->third_place_reward ?? '250 €' }}</p>
                </div>
            </div>
        @endif

        <!-- Full Leaderboard -->
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden mb-12 border border-[#00ffaa]/20">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#00ffaa]/10">
                    <tr>
                        <th class="p-4 text-center text-[#00ffaa] font-bold text-lg">RANK</th>
                        <th class="p-4 text-[#00ffaa] font-bold text-lg">USERNAME</th>
                        <th class="p-4 text-[#00ffaa] font-bold text-lg">PRIZE</th>
                        <th class="p-4 text-[#00ffaa] font-bold text-lg">WAGERED</th>
                        <th class="p-4 text-[#00ffaa] font-bold text-lg">WEIGHTED WAGER</th>
                        <th class="p-4 text-[#00ffaa] font-bold text-lg">FAVORITE GAME</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-[#00ffaa]/10">
                    @foreach ($entries as $i => $entry)
                        <tr class="{{ $i < 3 ? 'bg-[#00ffaa]/5' : 'bg-gray-900/30' }} table-row-hover">
                            <td class="p-4 text-center text-lg font-bold {{ $i === 0 ? 'text-[#00ffaa]' : ($i === 1 ? 'text-gray-300' : ($i === 2 ? 'text-amber-400' : 'text-white')) }}">
                                {{ $i + 1 }}
                            </td>
                            <td class="p-4 font-medium">
                                {{ substr($entry->username, 0, 2) . '****' . substr($entry->username, -2) }}
                            </td>
                            <td class="p-4">
                                @if ($i === 0)
                                    <span class="text-yellow-400 font-bold">{{ $leaderboard->first_place_reward ?? '1000 €' }}</span>
                                @elseif ($i === 1)
                                    <span class="text-gray-300 font-bold">{{ $leaderboard->second_place_reward ?? '500 €' }}</span>
                                @elseif ($i === 2)
                                    <span class="text-amber-400 font-bold">{{ $leaderboard->third_place_reward ?? '250 €' }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-4 font-mono">{{ number_format($entry->wagered, 2) }}$</td>
                            <td class="p-4 font-mono text-[#00ffaa] font-bold">{{ number_format($entry->weighted_wagered, 2) }} €</td>
                            <td class="p-4 text-gray-300">{{ \Illuminate\Support\Str::limit($entry->favorite_game_title, 30) }}</td>
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
                    const now = new Date().getTime();
                    const distance = end - now;

                    if (distance <= 0) {
                        countdown.textContent = 'COMPETITION ENDED!';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdown.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }

                setInterval(updateCountdown, 1000);
                updateCountdown();
            </script>
        @endif
    @endif
</div>

<!-- Rules Modal -->
<div id="rulesModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-gray-900/90 border border-[#00ffaa]/30 rounded-xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-[#00ffaa] neon-text">COMPETITION RULES</h2>
            <button onclick="closeRulesModal()" class="text-gray-400 hover:text-[#00ffaa] text-2xl transition duration-200">&times;</button>
        </div>

        <div class="text-left space-y-4 text-white/90">
            <p>Your wagers on Roobet will count towards the leaderboard at the following weights based on the games you are playing. This helps prevent leaderboard abuse.</p>

            <ul class="list-disc pl-6 space-y-2">
                <li>Games with an RTP of 97% or less will contribute <span class="text-[#00ffaa] font-bold">100%</span> of the amount wagered to the leaderboard.</li>
                <li>Games with an RTP above 97% will contribute <span class="text-yellow-400 font-bold">50%</span> of the amount wagered to the leaderboard.</li>
                <li>Games with an RTP of 98% and above will contribute <span class="text-red-400 font-bold">10%</span> of the amount wagered to the leaderboard.</li>
            </ul>

            <p class="font-bold text-[#00ffaa]">Participants must wager a minimum of $2,000 to qualify for prizes.</p>

            <p class="pt-4 border-t border-[#00ffaa]/20">We are fair to our users, please do not abuse the leaderboard. Attempts to abuse the leaderboard will be voided, and banned. Please play responsibly and fairly.</p>
        </div>

        <div class="mt-8 text-center">
            <button onclick="closeRulesModal()" class="bg-transparent border-2 border-[#00ffaa] text-[#00ffaa] font-bold py-2 px-6 rounded-lg btn-hover transition-all duration-300">
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
