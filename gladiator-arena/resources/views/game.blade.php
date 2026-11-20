<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Арена Гладиаторов</title>
    <link rel="stylesheet" href="/game.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚔️ Арена Гладиаторов</h1>
            <div class="stage-badge">РАУНД #{{ $stage }}</div>
        </div>

        <div class="arena">
            <div class="fighter">
                @if(!empty($lastAction['botDmg']))
                    <div class="dmg-popup">-{{ $lastAction['botDmg'] }}</div>
                @endif
                <div class="avatar-wrap">
                    <div class="avatar {{ !empty($lastAction['botDmg']) ? 'shake' : '' }}">🥷</div>
                </div>
                <h3>{{ $player->getName() }} @if($player->isBlocking()) <span class="shield-badge">🛡️ Блок</span> @endif</h3>
                <div class="bar-label"><span>❤️ Здоровье</span><span>{{ $player->getHp() }} / {{ $player->getMaxHp() }}</span></div>
                <div class="bar-bg"><div class="hp-fill" style="width: {{ ($player->getHp() / $player->getMaxHp()) * 100 }}%"></div></div>
                <div class="bar-label"><span>⚡ Выносливость</span><span>{{ $player->getStamina() }}%</span></div>
                <div class="bar-bg" style="height: 10px;"><div class="stam-fill" style="width: {{ $player->getStamina() }}%"></div></div>
            </div>

            <div class="vs-circle">VS</div>

            <div class="fighter">
                @if(!empty($lastAction['playerDmg']))
                    <div class="dmg-popup">-{{ $lastAction['playerDmg'] }}</div>
                @endif
                <div class="avatar-wrap">
                    <div class="avatar {{ !empty($lastAction['playerDmg']) ? 'shake' : '' }}">{{ $bot->getAvatar() }}</div>
                </div>
                <h3>{{ $bot->getName() }} @if($bot->isBlocking()) <span class="shield-badge">🛡️ Блок</span> @endif</h3>
                <div class="bar-label"><span>❤️ Здоровье</span><span>{{ $bot->getHp() }} / {{ $bot->getMaxHp() }}</span></div>
                <div class="bar-bg"><div class="hp-fill" style="width: {{ ($bot->getHp() / $bot->getMaxHp()) * 100 }}%"></div></div>
                <div class="bar-label"><span>⚡ Выносливость</span><span>{{ $bot->getStamina() }}%</span></div>
                <div class="bar-bg" style="height: 10px;"><div class="stam-fill" style="width: {{ $bot->getStamina() }}%"></div></div>
            </div>
        </div>

        @if($player->isAlive())
            <form method="POST" action="{{ route('game.attack') }}" class="controls">
                @csrf
                @foreach($moves as $key => $move)
                    <button type="submit" name="move" value="{{ $key }}" class="btn-move">
                        {{ $move->getLabel() }}
                        <small>Затраты сил: -{{ $move->getStaminaCost() }}</small>
                    </button>
                @endforeach
            </form>
        @else
            <div class="game-over">
                <h2 style="color: #ff3838; margin-bottom: 15px;">💀 Вы пали на песке Колизея!</h2>
                <form method="POST" action="{{ route('game.restart') }}">
                    @csrf
                    <button type="submit" class="btn-move" style="max-width: 280px;">🔄 Сразиться снова</button>
                </form>
            </div>
        @endif

        <div class="logs">
            <strong>📜 Хроника сражения:</strong>
            <ul>
                @foreach($logs as $log)
                    <li>{{ $log }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>