# Принципы, паттерны и стили ООП в игре «Арена Гладиаторов»

## 1. Абстракция (`app/Game/Fighters/Fighter.php`)
Выделение общих характеристик и поведения всех бойцов в базовый абстрактный класс, запрещающий создавать «абстрактного» бойца напрямую.
```php
abstract class Fighter
{
    public function __construct(
        protected string \$name,
        protected string \$avatar,
        protected int \$hp,
        protected int \$maxHp
    ) {}

    public function isAlive(): bool { return \$this->hp > 0; }
}
```

## 2. Наследование (`app/Game/Fighters/PlayerGladiator.php`, `BotGladiator.php`)
Классы игрока и соперников наследуют общую логику здоровья, выносливости и получения урона от `Fighter`.
```php
class PlayerGladiator extends Fighter
{
    public function __construct(string name = 'Спартак', int hp = 100, int maxHp = 100, int stamina = 100)
    {
        parent::__construct(name, '🥷', hp, maxHp, stamina);
    }
}
```

## 3. Инкапсуляция (`app/Game/Fighters/Fighter.php`)
Скрытие внутреннего состояния (`$hp`, `$isBlocking`) и контроль его изменения через методы (например, автоматический расчет среза урона при активном блоке).
```php
public function receiveDamage(int \$damage): int
{
    if (\(this->isBlocking) {\)damage = (int) floor(\(damage * 0.25);\)this->isBlocking = false;
    }
    \$this->hp = max(0, this->hp - damage);
    return \$damage;
}
```

## 4. Полиморфизм (`app/Game/Moves/CombatMoveInterface.php`)
Единый интерфейс для всех приемов боя (`HeavySmashMove`, `QuickSlashMove`, `ShieldBlockMove`). Контроллер вызывает метод `execute()` одинаково для любого выбранного действия.
```php
interface CombatMoveInterface
{
    public function getLabel(): string;
    public function execute(Fighter attacker, Fighter defender): array;
}
```

## 5. Паттерн «Стратегия» (`app/Game/Ai/SmartBotStrategy.php`)
Вынесение логики принятия решений искусственным интеллектом в отдельный класс, который анализирует текущее здоровье и выносливость бота.
```php
class SmartBotStrategy
{
    public function chooseMove(Fighter bot, Fighter player): CombatMoveInterface
    {
        if (\$bot->getHp() < 30 && rand(1, 100) <= 50) return new ShieldBlockMove();
        if (\$bot->getStamina() >= 30 && rand(1, 100) <= 60) return new HeavySmashMove();
        return new QuickSlashMove();
    }
}
```