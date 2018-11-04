<?php

namespace XFizzer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use XFizzer\Main;

class Hub extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("hub", "Go to Spawn", "Usage: /hub", ["spawn"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $x = 15;
            $y = 75;
            $z = 196;
            $level = $this->plugin->getServer()->getLevelByName("world");
            $pos = new Position($x, $y, $z, $level);
            $sender->teleport($pos);
        }
    }
}