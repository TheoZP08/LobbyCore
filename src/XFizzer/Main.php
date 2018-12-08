<?php

namespace XFizzer;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use XFizzer\Commands\Hub;
use XFizzer\Events\EventListener;
use XFizzer\Stats\StatsListener;

class Main extends PluginBase
{
    public $stats;

    public function onLoad(): void
    {
        API::$main = $this;
    }

    public function onEnable(): void
    {
        $this->getLogger()->info('LobbyCore by XFizzer loaded');
        $this->Events();
        $this->Commands();

        $this->stats = new Config($this->getDataFolder() . "stats.yml", Config::YAML, array());
        if (!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
    }

    public function onDisable()
    {
        $this->getLogger()->info('LobbyCore disabled');
    }

    public function Events()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StatsListener($this), $this);
    }

    public function Commands()
    {
        $this->getServer()->getCommandMap()->register("hub", new Hub($this));
    }
}