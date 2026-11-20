<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game\Fighters\{PlayerGladiator, BotGladiator};
use App\Game\Moves\{HeavySmashMove, QuickSlashMove, ShieldBlockMove};
use App\Game\Ai\SmartBotStrategy;

class GameController extends Controller
{
    private function getMoves(): array
    {
        return [
            'quick' => new QuickSlashMove(),
            'heavy' => new HeavySmashMove(),
            'block' => new ShieldBlockMove(),
        ];
    }

    private function getBotByStage(int $stage): BotGladiator
    {
        return match ($stage) {
            1 => new BotGladiator('Громила Брут', '👹', 80, 80),
            2 => new BotGladiator('Тень Арены', '🥷', 110, 110),
            default => new BotGladiator('Чемпион Колизея', '👑', 150, 150),
        };
    }

    private function loadArena(): array
    {
        if (!session()->has('arena')) {
            $this->resetArena();
        }
        $s = session('arena');

        $player = new PlayerGladiator($s['player']['name'], $s['player']['hp'], $s['player']['maxHp'], $s['player']['stamina']);
        $player->setBlocking($s['player']['blocking']);

        $bot = new BotGladiator($s['bot']['name'], $s['bot']['avatar'], $s['bot']['hp'], $s['bot']['maxHp'], $s['bot']['stamina']);
        $bot->setBlocking($s['bot']['blocking']);

        return [$player, $bot, $s['stage'], $s['logs'], $s['lastAction'] ?? null];
    }

    private function saveArena(PlayerGladiator $player, BotGladiator $bot, int $stage, array $logs, ?array $lastAction = null): void
    {
        session(['arena' => [
            'player' => [
                'name' => $player->getName(), 'hp' => $player->getHp(),
                'maxHp' => $player->getMaxHp(), 'stamina' => $player->getStamina(),
                'blocking' => $player->isBlocking()
            ],
            'bot' => [
                'name' => $bot->getName(), 'avatar' => $bot->getAvatar(),
                'hp' => $bot->getHp(), 'maxHp' => $bot->getMaxHp(),
                'stamina' => $bot->getStamina(), 'blocking' => $bot->isBlocking()
            ],
            'stage' => $stage,
            'logs' => array_slice($logs, -6),
            'lastAction' => $lastAction
        ]]);
    }

    private function resetArena(): void
    {
        $this->saveArena(new PlayerGladiator(), $this->getBotByStage(1), 1, ['Бой начался! Выберите прием.']);
    }

    public function index()
    {
        [$player, $bot, $stage, $logs, $lastAction] = $this->loadArena();
        return view('game', [
            'player' => $player,
            'bot' => $bot,
            'stage' => $stage,
            'logs' => $logs,
            'moves' => $this->getMoves(),
            'lastAction' => $lastAction
        ]);
    }

    public function attack(Request $request)
    {
        [$player, $bot, $stage, $logs] = $this->loadArena();
        $moves = $this->getMoves();
        $moveKey = $request->input('move');
        $lastAction = ['playerDmg' => 0, 'botDmg' => 0];

        if (isset($moves[$moveKey]) && $player->isAlive() && $bot->isAlive()) {
            $pRes = $moves[$moveKey]->execute($player, $bot);
            $logs[] = $pRes['text'];
            $lastAction['playerDmg'] = $pRes['damage'];

            if ($bot->isAlive()) {
                $ai = new SmartBotStrategy();
                $botMove = $ai->chooseMove($bot, $player);
                $bRes = $botMove->execute($bot, $player);
                $logs[] = $bRes['text'];
                $lastAction['botDmg'] = $bRes['damage'];
            } else {
                $stage++;
                $bot = $this->getBotByStage($stage);
                $player->heal(30);
                $logs[] = "🏆 ПОБЕДА! Следующий соперник: {$bot->getName()} (+30 HP герою)!";
            }
        }

        $this->saveArena($player, $bot, $stage, $logs, $lastAction);
return redirect()->route('game.index');
}
public function restart()
{
$this->resetArena();
return redirect()->route('game.index');
}
}