<?php

namespace XFizzer\Task;

use Miste\scoreboardspe\API\Scoreboard;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;

class UpdateScoreboardTask extends Task
{
    /* @var Scoreboard $scoreboard */
    private $scoreboard;
    /* @var Player $player */
    private $player;

    public function __construct(Scoreboard $scoreboard, Player $player)
    {
        $this->scoreboard = $scoreboard;
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
        if (!$this->player->isOnline()) {
            $this->getHandler()->cancel();
        }
        $scoreboard = $this->scoreboard;
        $scoreboard->setLine($this->player, 0, 'Online: ' . count(Server::getInstance()->getOnlinePlayers()));
        $scoreboard->setLine($this->player, 2, 'Tokens: ');
        $scoreboard->setLine($this->player, 4, TF::GRAY . date('Y/m/d H:i:s'));
    }
}